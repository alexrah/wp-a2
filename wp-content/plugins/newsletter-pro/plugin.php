<?php

/*
  Plugin Name: Newsletter Pro
  Plugin URI: http://www.satollo.net/plugins/newsletter
  Description: Newsletter is a cool plugin to create your own subscriber list, to send newsletters, to build your business. <strong>Before update give a look to <a href="http://www.satollo.net/plugins/newsletter#update">this page</a> to know what's changed.</strong>
  Version: 2.5.2.2
  Author: Satollo
  Author URI: http://www.satollo.net
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.

  Copyright 2011 Stefano Lissa (email: info@satollo.net, web: http://www.satollo.net)
 */

define('NEWSLETTER_LIST_MAX', 9);
define('NEWSLETTER_PROFILE_MAX', 19);

global $newsletter;
$newsletter = new Newsletter();

class Newsletter {
    const VERSION = 250;

    var $time_limit;
    var $email_limit = 10; // Per run
    var $relink_email_id;
    var $relink_user_id;
    var $mailer;
    var $options_main;
    var $message;
    var $user;
    var $error;
    var $feed_skip;
    var $theme;

    // Theme autocomposer variables
    var $theme_max_posts;
    var $theme_excluded_categories; // comma separated ids (eventually negative to exclude)
    var $theme_posts; // WP_Query object


    function Newsletter() {
        global $wpdb;

        // Early possible
        $max_time = (int) (@ini_get('max_execution_time') * 0.9);
        if ($max_time == 0) $max_time = 600;
        $this->time_limit = time() + $max_time;

        register_activation_hook(__FILE__, array(&$this, 'hook_activate'));
        register_deactivation_hook(__FILE__, array(&$this, 'hook_deactivate'));

        add_action('init', array(&$this, 'hook_init'));
        add_action('admin_init', array(&$this, 'hook_admin_init'));
        add_action('mailer_bounce_email', array(&$this, 'mailer_bounce_email'));

        add_filter('cron_schedules', array(&$this, 'hook_cron_schedules'), 1000);
        add_action('newsletter', array(&$this, 'hook_newsletter'), 1);

        // This specific event is created by "Feed by mail" panel on configuration
        add_action('newsletter_feed', array(&$this, 'hook_newsletter_feed'));
        add_action('template_redirect', array(&$this, 'hook_template_redirect'));
        add_action('wp_head', array(&$this, 'hook_wp_head'));
        add_shortcode('newsletter', array(&$this, 'shortcode_newsletter'));
        add_shortcode('newsletter_lock', array(&$this, 'shortcode_newsletter_lock'));
        add_shortcode('newsletter_form', array(&$this, 'shortcode_newsletter_form'));
        add_shortcode('newsletter_embed', array(&$this, 'shortcode_newsletter_form'));
        add_action('shutdown', array(&$this, 'hook_shutdown'));
        if (is_admin ()) {
            add_action('admin_menu', array(&$this, 'hook_admin_menu'));
            add_action('admin_head', array(&$this, 'hook_admin_head'));
        }

        $this->options_main = get_option('newsletter_main', array());
    }

    function hook_admin_init() {
        global $wpdb;
        if ($_REQUEST['act'] == 'export' && check_admin_referer()) {
            include 'plugin-export.inc.php';
        }

        if (strpos($_GET['page'], 'newsletter-pro/') === 0) {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_style('thickbox');
        }
    }

    function hook_admin_head() {
        if (strpos($_GET['page'], 'newsletter-pro/') === 0) {
            echo '<link type="text/css" rel="stylesheet" href="' .
            get_option('siteurl') . '/wp-content/plugins/newsletter-pro/style.css"/>';
        }
    }

    function hook_admin_menu() {
        include 'plugin-menu.inc.php';
    }

    function hook_wp_head() {
        include 'plugin-head.inc.php';
    }

    function hook_newsletter_feed($force=false) {
        global $wpdb, $post;

        $this->log();
        if (!$this->check_transient('newsletter_feed', 3600)) return;

        $options_feed = get_option('newsletter_feed');

        if (!$force && $options_feed['day_' . date('N')] == 0) return;

        $last_time = get_option('newsletter_feed_last_time', 0);

        $posts = get_posts(array('showposts' => 5, 'post_status' => 'publish'));
        if (empty($posts)) {
            // Rather odd, it means the blog has not published posts...
            $this->error('No posts found!?');
            return;
        }
        setup_postdata($posts[0]);

        if ($last_time >= $this->m2t($posts[0]->post_date_gmt)) {
            $this->log('Nothing to send');
            return;
        }
        update_option('newsletter_feed_last_time', time());

        $subject = trim($options_feed['subject']);
        if (empty($subject)) $subject = $posts[0]->post_title;

        $total = (int) $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where feed=1");
        $email = array('type' => 'feed', 'status' => 'sending', 'subject' => $subject, 'message' => '', 'theme' => $options_feed['theme'],
            'track' => $options_feed['track'],
            'total' => $total);

        $wpdb->insert($wpdb->prefix . 'newsletter_emails', $email);
    }

    function check_transient($name, $time) {
        if (get_transient($name) !== false) {
            $this->log('Called too quickly');
            return false;
        }
        set_transient($name, 1, $time);
        return true;
    }

    function hook_newsletter() {
        global $wpdb;

        $this->log();
        if (!$this->check_transient('newsletter', 60)) return;

        $max = $this->options_main['scheduler_max'];
        if (!is_numeric($max)) $max = 100;
        $this->email_limit = max(floor($max / 12), 1);

        $this->set_limits();

        // Retrieve all email in "sending" status
        $emails = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter_emails where status='sending' order by id asc");
        foreach ($emails as $email) {
            if (!$this->send($email)) return;
        }

        $this->followup_send();
    }

    function send($email, $users = null, $echo=false) {
        global $wpdb;
        
        if ($email->type == 'feed') return $this->feed_send($email, $users);

        if ($this->limits_exceeded()) return false;

        if ($users == null) {
            // Fake value representing the WordPress users target
            if ($email->list == -1) {
                if (!empty($email->query)) $query = "select * from " . $wpdb->prefix . "users " . $email->query . " and id>" . $email->last_id . " order by id limit " . $this->email_limit;
                else $query = "select * from " . $wpdb->prefix . "users where id>" . $email->last_id . " order by id limit " . $this->email_limit;
                $this->log($query, 3);
                $wp_users = $wpdb->get_results($query);
                $users = array();
                foreach ($wp_users as &$wp_user) {
                    $user = new stdClass();
                    $user->email = $wp_user->user_email;
                    $user->name = $wp_user->user_login;
                    $user->id = -$wp_user->ID;
                    $users[] = $user;
                }
            }
            else {
                if (!empty($email->query)) $query = "select * from " . $wpdb->prefix . "newsletter " . $email->query;
                else {
                    $query = "select * from " . $wpdb->prefix . "newsletter where status='C'";
                    if ($email->list != 0) $query .= " and list_" . $email->list . "=1";
                    if (!empty($email->sex)) $query .= " and sex='" . $email->sex . "'";
                }
                $query .= " and id>" . $email->last_id . " order by id limit " . $this->email_limit;
                $this->log($query, 3);
                $users = $wpdb->get_results($query);
            }
        }

        if (empty($users)) {
            $this->log('No more users');
            $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set status='sent' where id=" . $email->id);
            return;
        }

        foreach ($users as $user) {
            $headers = array('List-Unsubscribe' => '<' .
                $this->add_qs($this->options_main['url'], 'na=u&ni=' . $user->id . '&nt=' . $user->token) . '>');

            $m = $this->execute($email->message, $user);
            if (empty($m)) continue;
            $m = $this->replace($m, $user);

            if ($email->track == 1) $m = $this->relink($m, $email->id, $user->id);

            // Add CSS
            if ($email->theme[0] == '*') $file = 'newsletter-custom/themes/' . substr($email->theme, 1) . '/style.css';
            else $file = 'newsletter-pro/themes/' . $email->theme . '/style.css';
            $css = @file_get_contents(ABSPATH . 'wp-content/plugins/' . $file);

            $m = '<html><head><style type="text/css">' . $css .
                '</style></head><body>' . $m . '</body></html>';

            $s = $this->replace($email->subject, $user);
            $x = $this->mail($user->email, $s, $m, true, $headers, 2);

            $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set sent=sent+1, last_id=" . abs($user->id) . " where id=" . $email->id);
            $this->email_limit--;
            if ($this->limits_exceeded()) return false;
        }
        return true;
    }

    function feed_send($email, $users = null) {
        global $wpdb, $newsletter_feed_user, $newsletter_feed_skip;

        if ($this->limits_exceeded()) return false;

        if ($users == null) {
            $query = "select * from " . $wpdb->prefix . "newsletter where status='C' and feed=1" .
                    " and id>" . $email->last_id . " order by id limit " . $this->email_limit;

            $users = $wpdb->get_results($query);
        }

        if (empty($users)) {
            $this->log('No more users');
            $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set status='sent' where id=" . $email->id);
            return;
        }

        $this->feed_options = get_option('newsletter_feed');
        foreach ($users as $user) {
            $this->user = $user; // Used by newsletter theme
            $newsletter->feed_skip = false;
            $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set sent=sent+1, last_id=" . $user->id . " where id=" . $email->id);
            $wpdb->query("update " . $wpdb->prefix . "newsletter set feed_time=" . time() . " where id=" . $user->id);

            if ($email->theme[0] == '*') $file = ABSPATH . 'wp-content/plugins/newsletter-custom/themes-feed/' . substr($email->theme, 1) .
                        '/theme.php';
            else $file = dirname(__FILE__) . '/themes-feed/' . $email->theme . '/theme.php';

            // Include the labels, language dependend
            @include(dirname($file) . '/en_US.php');
            if (defined('WPLANG') && WPLANG != '') @include(dirname($file) . '/' . WPLANG . '.php');

            ob_start();
            @include($file);
            $m = ob_get_contents();
            ob_end_clean();

            if ($newsletter->feed_skip) {
                $this->log('Skipping', 3);
                continue;
            }

            $m = $this->replace($m, $user);

            if ($email->track == 1) $m = $this->relink($m, $email->id, $user->id);

            $s = $this->replace($email->subject, $user);

            //$x = $this->mail('satollo+test@gmail.com', $s, $m, true, $headers, 2);
            $x = $this->mail($user->email, $s, $m, true, $headers, 2);

            $this->email_limit--;
            if ($this->limits_exceeded()) return false;
        }
        return true;
    }

    function followup_send($users = null, $options=null) {
        global $wpdb;

        if ($this->limits_exceeded()) return false;

        if ($options == null) $options = get_option('newsletter_followup', array());
        $this->log('Enabled: ' . $options['enabled']);

        if ($users == null && $options['enabled'] == 0) return;

        if ($users == null) {
            $this->log('Loading follow up users');
            $query = "select * from " . $wpdb->prefix . "newsletter where status='C' and " .
                    "followup=1 and followup_time<" . time() . " order by id limit " . $this->email_limit;
            $this->log($query, 3);
            $users = $wpdb->get_results($query);
        }

        $this->log($users, 3);


        foreach ($users as $user) {
            $this->log('Processing user ' . $user->id, 3);
            $this->log($user);

            $this->user = $user;
            $user->followup_step++; // Step to do

            if ($user->followup_step <= 9) {

                $this->query("update " . $wpdb->prefix . "newsletter set followup_step=followup_step+1" .
                        ", followup_time=" . (time() + $options['interval'] * 3600) . " where id=" . $user->id);
                $subject = $options['step_' . $user->followup_step . '_subject'];
                $subject = $this->replace($subject, $user);

                if ($options['theme'][0] == '*') $file = ABSPATH . 'wp-content/plugins/newsletter-custom/themes-followup/' . substr($options['theme'], 1) .
                            '/theme.php';
                else $file = dirname(__FILE__) . '/themes-followup/' . $options['theme'] . '/theme.php';

                // Include the labels, language dependend
                @include(dirname($file) . '/en_US.php');
                if (defined('WPLANG') && WPLANG != '') @include(dirname($file) . '/' . WPLANG . '.php');

                ob_start();
                @include($file);
                $message = ob_get_contents();
                ob_end_clean();


                $message = str_replace('{message}', $options['step_' . $user->followup_step . '_message'], $message);

                $message = $this->replace($message, $user);
                if ($options['track'] == 1) $message = $this->relink($message, 'f' . $user->followup_step, $user->id);

                $this->mail($user->email, $subject, $message, true, null, 2);
                //$this->mail('satollo+test@gmail.com', $subject, $message, true, null, 2);
                $this->email_limit--;
                if ($this->limits_exceeded()) return false;
            }
            else {
                $wpdb->query("update " . $wpdb->prefix . "newsletter set followup=2 where id=" . $user->id);
            }
        }
    }

    function execute($text, $user=null) {
        global $wpdb;
        ob_start();
        $r = eval('?' . '>' . $text);
        if ($r === false) {
            $this->error = 'Error while executing a PHP expression in a message body. See log file.';
            $this->log('Error on execution of ' . $text, 1);
            ob_end_clean();
            return false;
        }

        return ob_get_clean();
    }

    /**
     * Return true if the execution timeout is reached or the maximum number of email
     * has been sent.
     */
    function limits_exceeded() {
        if (time() > $this->time_limit) {
            $this->log('Timeout', 3);
            return true;
        }
        if ($this->email_limit <= 0) {
            $this->log('Email limit reached', 3);
            return true;
        }
        return false;
    }

    function mail($to, $subject, $message, $html=true, $headers=null, $priority=0) {

        $this->log('To: ' . $to, 3);
        $this->log('Subject: ' . $subject, 3);
        if (empty($subject)) {
            $this->log('Subject empty, skipped', 3);
            return true;
        }

        if (!empty($this->options_main['receiver'])) $to = $this->options_main['receiver'];

        // If not SMTP is configured, send via wp_mail()
        if ($this->options_main['smtp_enabled'] == 0) {
            $this->log('SMTP not enabled', 3);
            $h = '';
            if (!empty($headers)) {
                foreach ($headers as $key => $value)
                    $h .= $key . ': ' . $value . "\n";
            }

            $h .= "From: " . $this->options_main['sender_name'] . " <" . $this->options_main['sender_email'] . ">\n";

            if (!empty($this->options_main['reply_to'])) {
                $h .= "Reply-To: " . $this->options_main['reply_to'] . "\n";
            }
            if (!empty($this->options_main['return_path'])) {
                $h .= "Return-Path: " . $this->options_main['return_path'] . "\n";
            }
            $h .= "X-MailerPriority: " . $priority . "\n";
            $h .= "X-MailerGroup: newsletter\n";
            $h .= "MIME-Version: 1.0\n";
            if ($html) $h .= "Content-type: text/html; charset=UTF-8\n";
            else $h .= "Content-type: text/plain; charset=UTF-8\n";
            $this->log('Headers: ' . $h, 3);
            $r = wp_mail($to, $subject, $message, $h);
            $this->log($r ? 'Mail sent' : 'Mail sending failed', 1);
            return $r;
        }

        if ($this->mailer == null) $this->mailer_init();

        $this->mailer->IsHTML($html);
        $this->mailer->Body = $message;

        $this->mailer->Subject = $subject;

        $this->mailer->ClearCustomHeaders();
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $this->mailer->AddCustomHeader($key . ': ' . $value);
            }
        }

        $this->mailer->ClearAddresses();
        $this->mailer->AddAddress($to);
        $this->mailer->Send();
        if ($this->mailer->IsError()) {
            $this->log($this->mailer->ErrorInfo, 1);
            return false;
        }
        $this->log('Mail sent via SMTP');
        return true;
    }

    function mailer_init() {
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $this->mailer = new PHPMailer();

        $this->mailer->IsSMTP();
        $this->mailer->Host = $this->options_main['smtp_host'];
        if (!empty($this->options_main['smtp_port'])) $this->mailer->Port = (int) $this->options_main['smtp_port'];

        if (!empty($this->options_main['smtp_user'])) {
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->options_main['smtp_user'];
            $this->mailer->Password = $this->options_main['smtp_pass'];
        }
        $this->mailer->SMTPKeepAlive = true;

        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->From = $this->options_main['sender_email'];
        if (!empty($this->options_main['return_path'])) $this->mailer->Sender = $this->options_main['return_path'];
        if (!empty($this->options_main['reply_to'])) $this->mailer->AddReplyTo($this->options_main['reply_to']);

        $this->mailer->FromName = $this->options_main['sender_name'];
    }

    function relink($text, $email_id, $user_id) {
        $this->relink_email_id = $email_id;
        $this->relink_user_id = $user_id;
        return preg_replace_callback('/(<[aA][^>]+href=["\'])([^>"\']+)(["\'][^>]*>)(.*?)(<\/[Aa]>)/', array($this, 'relink_callback'), $text);
    }

    function relink_callback($matches) {

        $href = str_replace('&amp;', '&', $matches[2]);
        if (strpos($href, 'na=') !== false && strpos($href, 'ni=') !== false && strpos($href, 'nt=') !== false) return $matches[0];

        $anchor = trim(str_replace(';', ' ', $matches[4]));
        if (!$anchor) $anchor = '-';

        $url = get_option('home') . '/';
        $url .= '?na=r&amp;nr=' .
                urlencode(base64_encode($this->relink_email_id . ';' . $this->relink_user_id . ';' .
                                $anchor . ';' . $href));

        return $matches[1] . $url . $matches[3] . $matches[4] . $matches[5];
    }

    /**
     * Levels are: 1 for errors, 2 for normal activity, 3 for debug.
     */
    function log($text='', $level=2) {
        if ((int) $this->options_main['logs'] < $level) return;

        $db = debug_backtrace(false);
        $time = date('d-m-Y H:i:s ');
        switch ($level) {
            case 1: $time .= '- ERROR';
                break;
            case 2: $time .= '- INFO ';
                break;
            case 3: $time .= '- DEBUG';
                break;
        }
        if (is_array($text) || is_object($text)) $text = print_r($text, true);
        file_put_contents(dirname(__FILE__) . '/log.txt', $time . ' - ' . $db[1]['function'] . ' - ' . $text . "\n", FILE_APPEND | FILE_TEXT);
    }

    function hook_activate() {
        global $wpdb;
        $this->log("Attivazione");
        include 'plugin-activate.inc.php';
    }

    function hook_deactivate() {
        wp_clear_scheduled_hook('newsletter');
        wp_clear_scheduled_hook('newsletter_feed');
    }

    function hook_cron_schedules($schedules) {
        $schedules['newsletter'] = array(
            'interval' => 300, // seconds
            'display' => 'Newsletter'
        );
        return $schedules;
    }

    /**
     * Generate the profile editing form.
     */
    function profile_form($user) {
        $options = get_option('newsletter_profile');

        $buffer .= '<div class="newsletter newsletter-profile">';
        $buffer .= '<form action="" method="post"><input type="hidden" name="na" value="ps"/>';
        $buffer .= '<table>';
        $buffer .= '<tr><th align="right">' . $options['email'] . '</th><td><input class="newsletter-email" type="text" size="30" name="ne" value="' . htmlspecialchars($user->email) . '"/></td></tr>';
        if ($options['name_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . $options['name'] . '</th><td><input class="newsletter-firstname" type="text" size="30" name="nn" value="' . htmlspecialchars($user->name) . '"/></td></tr>';
        }
        if ($options['surname_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . $options['surname'] . '</th><td><input class="newsletter-lastname" type="text" size="30" name="ns" value="' . htmlspecialchars($user->surname) . '"/></td></tr>';
        }
        if ($options['sex_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . $options['sex'] . '</th><td><select name="nx" class="newsletter-sex">';
            //        if (!empty($options['sex_none'])) {
            //            $buffer .= '<option value="n"' . ($user->sex == 'n' ? ' selected' : '') . '>' . $options['sex_none'] . '</option>';
            //        }
            $buffer .= '<option value="f"' . ($user->sex == 'f' ? ' selected' : '') . '>' . $options['sex_female'] . '</option>';
            $buffer .= '<option value="m"' . ($user->sex == 'm' ? ' selected' : '') . '>' . $options['sex_male'] . '</option>';
            $buffer .= '</select></td></tr>';
        }

        // Profile
        for ($i = 1; $i <= 19; $i++) {
            if ($options['profile_' . $i . '_status'] == 0) continue;

            $buffer .= '<tr><th align="right">' . $options['profile_' . $i] . '</th><td>';
            //if ($options['list_type_' . $i] != 'public') continue;
            $field = 'profile_' . $i;

            if ($options['profile_' . $i . '_type'] == 'text') {
                $buffer .= '<input class="newsletter-profile newsletter-profile-' . $i . '" type="text" size="40" name="np' . $i . '" value="' . htmlspecialchars($user->$field) . '"/>';
            }

            if ($options['profile_' . $i . '_type'] == 'select') {
                $buffer .= '<select class="newsletter-profile newsletter-profile-' . $i . '" name="np' . $i . '">';
                $opts = explode(',', $options['profile_' . $i . '_options']);
                for ($i = 0; $i < count($opts); $i++) {
                    $opts[$i] = trim($opts[$i]);
                    $buffer .= '<option';
                    if ($opts[$i] == $user->$field) $buffer .= ' selected';
                    $buffer .= '>' . $opts[$i] . '</option>';
                }
                $buffer .= '</select>';
            }

            $buffer .= '</td></tr>';
        }

        // Lists
        $buffer .= '<tr><th>&nbsp;</th><td>';
        for ($i = 1; $i <= 9; $i++) {
            if ($options['list_' . $i . '_status'] == 0) continue;
            $buffer .= '<input type="checkbox" name="nl[]" value="' . $i . '"';
            $list = 'list_' . $i;
            if ($user->$list == 1) $buffer .= ' checked';
            $buffer .= '/> ' . htmlspecialchars($options['list_' . $i]) . '<br />';
        }
        $buffer .= '</td></tr>';

        // Follow up
        $options_followup = get_option('newsletter_followup');
        if ($options_followup['enabled'] == 1 && $options['followup_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . $options['followup'] . '</th><td>';
            $buffer .= '<input type="checkbox" name="followup"';
            if ($user->followup == 1) $buffer .= ' checked';
            $buffer .= '/></td></tr>';
        }

        // Feed by mail
        $options_feed = get_option('newsletter_feed');
        if ($options_feed['enabled'] == 1 && $options['feed_status'] >= 1) {
            $buffer .= '<tr><th align="right">' . $options['feed'] . '</th><td>';
            $buffer .= '<input type="checkbox" name="feed"';
            if ($user->feed == 1) $buffer .= ' checked';
            $buffer .= '/></td></tr>';
        }
        $buffer .= '<tr><td colspan="2" align="center"><input type="submit" value="' . $options['save'] . '"/></td></tr>';
        $buffer .= '</table></form></div>';

        return $buffer;
    }

    function subscription_form($action='s') {
        $options_profile = get_option('newsletter_profile');
        $options = get_option('newsletter');
        $buffer = '<div class="newsletter newsletter-subscription"><form method="post" action="' . $this->options_main['url'] . '" onsubmit="return newsletter_check(this)"><input type="hidden" name="na" value="' . $action . '"/>';
        $buffer .= '<table>';
        if ($options_profile['name_status'] == 2) {
            $buffer .= '<tr><th>' . $options_profile['name'] . '</th><td><input class="newsletter-firstname" type="text" name="nn" size="30"/></td></tr>';
        }
        if ($options_profile['surname_status'] == 2) {
            $buffer .= '<tr><th>' . $options_profile['surname'] . '</th><td><input class="newsletter-lastname" type="text" name="ns" size="30"/></td></tr>';
        }

        $buffer .= '<tr><th>' . $options_profile['email'] . '</th><td align="left"><input class="newsletter-email" type="text" name="ne" size="30"/></td></tr>';

        if ($options_profile['sex_status'] == 2) {
            $buffer .= '<tr><th>' . $options_profile['sex'] . '</th><td><select name="nx" class="newsletter-sex">';
            $buffer .= '<option value="m">' . $options_profile['sex_male'] . '</option>';
            $buffer .= '<option value="f">' . $options_profile['sex_female'] . '</option>';
            $buffer .= '</select></td></tr>';
        }
        $lists = '';
        for ($i = 1; $i <= 9; $i++) {
            if ($options_profile['list_' . $i . '_status'] != 2) continue;
            $lists .= '<input type="checkbox" name="nl[]" value="' . $i . '"/>&nbsp;' . $options_profile['list_' . $i] . '<br />';
        }
        if (!empty($lists)) $buffer .= '<tr><th>&nbsp;</th><td>' . $lists . '</td></tr>';

        // Extra profile fields
        for ($i = 1; $i <= 19; $i++) {
            if ($options_profile['profile_' . $i . '_status'] != 2) continue;
            if ($options_profile['profile_' . $i . '_type'] == 'text') {
                $buffer .= '<tr><th>' . $options_profile['profile_' . $i] . '</th><td><input class="newsletter-profile newsletter-profile-' . $i . ' type="text" size="30" name="np' . $i . '"/></td></tr>';
            }
            if ($options_profile['profile_' . $i . '_type'] == 'select') {
                $buffer .= '<tr><th>' . $options_profile['profile_' . $i] . '</th><td><select class="newsletter-profile newsletter-profile-' . $i . ' name="np' . $i . '">';
                $opts = explode(',', $options_profile['profile_' . $i . '_options']);
                for ($i = 0; $i < count($opts); $i++) {
                    $buffer .= '<option>' . trim($opts[$i]) . '</option>';
                }
                $buffer .= '</select></td></tr>';
            }
        }

        $buffer .= '<tr><td colspan="2" style="text-align: center">';
        if ($options_profile['privacy_status'] == 1) {
            $buffer .= '<input type="checkbox" name="ny"/>&nbsp;' . $options_profile['privacy'] . '<br />';
        }
        $buffer .= '<input type="submit" value="' . $options_profile['subscribe'] . '"/></td></tr>';
        $buffer .= '</table></form></div>';
        return $buffer;
    }

    function mailer_bounce_email($email) {
//    $recipients = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix .
//                            "newsletter where email=%s", strtolower($email)));
//    if (empty($recipients)) return;
//            newsletter_set_status($s->id, 'B');
//        }
//        else {
//            newsletter_log('No matching subscriber');
//        }
    }

    function hook_template_redirect() {

        if (empty($this->message)) return;

        // The message will be shown on main newsletter page
        if (!empty($this->options_main['url'])) return;

        // In the future the theme will be possible set elsewhere
        if (empty($this->theme)) $this->theme = $this->options_main['theme'];

        if ($this->theme[0] == '*') $file = ABSPATH . 'wp-content/plugins/newsletter-custom/themes-page/' . substr($this->$this->theme, 1) . '/theme.php';
        else $file = dirname(__FILE__) . '/themes-page/' . $this->theme . '/theme.php';

        // Include the labels, language dependend
        @include(dirname($file) . '/en_US.php');
        if (defined('WPLANG') && WPLANG != '') @include(dirname($file) . '/' . WPLANG . '.php');

        ob_start();
        @include($file);
        $m = ob_get_contents();
        ob_end_clean();

        echo $this->execute(str_replace('{message}', $this->message, $m));
        die();
    }

    function shortcode_newsletter() {
        if (!empty($this->message)) return $this->message;

        $options = get_option('newsletter');
        // If there is an "embedded" form, adjust it and ignore the standard subscription form
        if (stripos($options['subscription_text'], '<form') !== false) {
            $buffer = str_ireplace('<form', '<form method="post" action="' . $this->options_main['url'] . '" onsubmit="return newsletter_check(this)"', $options['subscription_text']);
            return str_ireplace('</form>', '<input type="hidden" name="na" value="s"/></form>', $buffer);
        }

        if (strpos($buffer, '{subscription_form}') !== false) return str_replace('{subscription_form}', $this->subscription_form(), $options['subscription_text']);
        else return $options['subscription_text'] . $this->subscription_form();

        return $buffer;
    }

    function shortcode_newsletter_form($attrs, $content) {
        return $this->form($attrs['form']);
    }

    function form($number=null) {
        if ($number == null) return $this->subscription_form();
        $options = get_option('newsletter_forms');
        $options_profile = get_option('newsletter_profile');

        $form = str_replace('{newsletter_url}', $this->options_main['url'], $options['form_' . $number]);

        $lists = '';
        for ($i = 1; $i <= 9; $i++) {
            if ($options_profile['list_' . $i . '_status'] != 2) continue;
            $lists .= '<input type="checkbox" name="nl[]" value="' . $i . '"/>&nbsp;' . $options_profile['list_' . $i] . '<br />';
        }
        $form = str_replace('{lists}', $lists, $form);
        return $form;
    }

    function hook_init() {
        global $cache_stop, $hyper_cache_stop, $lite_cache_stop, $wpdb;

        $action = $_REQUEST['na'];
        if (empty($action) || is_admin()) return;

        $cache_stop = true;
        $hyper_cache_stop = true;
        $lite_cache_stop = true;

        $this->log($action);

        if ($action == 'r') {
            $this->log(base64_decode($_GET['nr']));
            $url = explode(';', base64_decode($_GET['nr']), 4);
            $this->log($url);
            $wpdb->insert($wpdb->prefix . 'newsletter_stats',
                    array(
                        'email_id' => $url[0],
                        'newsletter_id' => $url[1],
                        'url' => $url[3],
                        'anchor' => $url[2]
                    )
            );
            $this->log($wpdb->last_error);
            header('Location: ' . $url[3]);
            die();
        }

        $options = get_option('newsletter', array()); // Subscription options, emails and texts

        if ($action == 'of') {
            echo $this->subscription_form('os');
            die();
        }

        // Subscription request from a subscription form (in page or widget), can be
        // a direct subscription with no confirmation
        if ($action == 's' || $action == 'os') {
            $email = $this->normalize_email(stripslashes($_REQUEST['ne']));
            if ($email == null) die('Wrong email');
            $user = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "newsletter where email=%s", $email));
            if ($user == null) {
                $this->log("not found");
                $user = array('email' => $email);

                $user['name'] = $this->normalize_name(stripslashes($_REQUEST['nn']));
                $user['surname'] = $this->normalize_name(stripslashes($_REQUEST['ns']));
                if (!empty($_REQUEST['nx'])) $user['sex'] = $_REQUEST['nx'][0];
                $user['referrer'] = $_REQUEST['nr'];
                
                $options_profile = get_option('newsletter_profile');

                // New profiles
                for ($i = 1; $i <= 19; $i++) {
                    if ($options_profile['profile_' . $i . '_status'] == 0) continue;
                    $user['profile_' . $i] = trim(stripslashes($_REQUEST['np' . $i]));
                }
                
                // Lists (field names are nl[] and values the list number so special forms with radio button can work)
                if (is_array($_REQUEST['nl'])) {
                    for ($i = 1; $i <= 9; $i++) {
                        if ($options_profile['list_' . $i . '_status'] != 2) continue;
                        if (in_array($i, $_REQUEST['nl'])) $user['list_' . $i] = 1;
                    }
                }

                $user['token'] = md5(rand());
                $user['ip'] = $_SERVER['REMOTE_ADDR'];
                $user['status'] = $options['noconfirmation'] == 1 ? 'C' : 'S';

                $options_followup = get_option('newsletter_followup', array());
                if ($options_followup['add_new'] == 1) {
                    $user['followup'] = 1;
                    $user['followup_time'] = time() + $options_followup['interval'] * 3600;
                }

                $options_feed = get_option('newsletter_feed', array());
                if ($options_feed['add_new'] == 1) $user['feed'] = 1;

                $wpdb->insert($wpdb->prefix . 'newsletter', $user);
                $user = $this->get_user($wpdb->insert_id); // back to an object

                // Old profiles
                $profile = $_REQUEST['np'];
                if (!empty($profile)) {
                    foreach ($profile as $key => $value) {
                    @$wpdb->insert($wpdb->prefix . 'newsletter_profiles', array(
                            'newsletter_id' => $user->id,
                            'name' => $key,
                            'value' => $value));
                    }
                }

                // Notification to admin (only for new confirmed subscriptions)
                if ($user->status == 'C') {
                    $this->notify_admin($user, 'Newsletter subscription');
                }
            }

            $this->log($user);

            $prefix = ($user->status == 'C') ? 'confirmed_' : 'confirmation_';
            $message = $options[$prefix . 'message'];
            $subject = $options[$prefix . 'subject'];

            //setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');

            $this->mail($user->email, $this->replace($subject, $user), $this->replace($message, $user));

            if ($user->status == 'C') {
                if (!empty($options['confirmed_url'])) {
                    header('Location: ' . $options['confirmed_url']);
                    die();
                }
                $this->message = $this->replace($options['confirmed_text'], $user);
            }
            else $this->message = $this->replace($options['subscribed_text'], $user);

            if ($action == 'os') {
                //echo $this->message;
                echo 'ok';
                die();
            }

            return;
        }

        if ($action == 'api-add') {
            $key = stripslashes($_REQUEST['nk']);
            if (empty($this->options_main['api_key']) || $key != $this->options_main['api_key']) die('Wrong API key');

            if (!is_email($_REQUEST['ne'])) die('Wrong email');
            $subscriber = array();
            $subscriber['name'] = stripslashes($_REQUEST['nn']);
            $subscriber['surname'] = stripslashes($_REQUEST['ns']);
            $subscriber['email'] = stripslashes($_REQUEST['ne']);

            if (!empty($_REQUEST['nl'])) {
               $add_lists = explode('|',$_REQUEST['nl']);
               foreach ($add_lists as $add_list) {
                   $subscriber['list_' . $add_list] = 1;
               }
            }
            $subscriber['status'] = 'C';
            $subscriber['token'] = md5(rand());
            $wpdb->insert($wpdb->prefix . 'newsletter', $user);
            die('ok');
        }

        if ($action == 'api-delete') {
            $key = stripslashes($_REQUEST['nk']);
            if (empty($newsletter->options_main['api_key']) || $key != $newsletter->options_main['api_key']) die('Wrong API key');

            $email = $this->normalize_email(stripslashes($_REQUEST['ne']));
            $r = $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter where email=%s", $email));
            die($r = 0 ? 'ko' : 'ok');
        }

        // Actions below need a user. This code loads the user checking parameter or cookies.
        $user = $this->check_user();
        if ($user == null) die('No user');

        if ($action == 'c') {
            setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
            $wpdb->query("update " . $wpdb->prefix . "newsletter set status='C' where id=" . $user->id);

            $this->message = $this->replace($options['confirmed_text'], $user);
            
            $this->mail($user->email, $this->replace($options['confirmed_subject'], $user), $this->replace($options['confirmed_message'], $user));
            $this->notify_admin($user, 'Newsletter subscription');

            if (!empty($options['confirmed_url'])) {
                header('Location: ' . $options['confirmed_url']);
                die();
            }

            return;
        }

        // Unsubscription request
        if ($action == 'u') {
            $this->message = $this->replace($options['unsubscription_text'], $user);
            return;
        }

        // Unsubscription confirm
        if ($action == 'uc') {
            setcookie("newsletter", "", time() - 3600);
            $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter where id=%d and token=%s", $user->id, $user->token));
            $this->message = $this->replace($options['unsubscribed_text'], $user);
            $this->mail($user->email, $this->replace($options['unsubscribed_subject'], $user), $this->replace($options['unsubscribed_message'], $user));
            $this->notify_admin($user, 'Newsletter cancellation');
            return;
        }

        // Profile saving
        if ($action == 'ps') {
            $options_profile = get_option('newsletter_profile', array());
            // Javascript checked
            if (!$this->is_email($_REQUEST['ne'])) die('Wrong email address.');

            // General data
            $data['email'] = $this->normalize_email(stripslashes($_REQUEST['ne']));
            $data['name'] = $this->normalize_name(stripslashes($_REQUEST['nn']));
            $data['surname'] = $this->normalize_name(stripslashes($_REQUEST['ns']));
            if ($options_profile['sex_status'] >= 1) {
                $data['sex'] = $_REQUEST['nx'][0];
                // Wrong data injection check
                if ($data['sex'] != 'm' && $data['sex'] != 'f') die('Wrong sex field');
            }

            // Lists
            if (is_array($_REQUEST['nl'])) {
                for ($i = 1; $i <= 9; $i++) {
                    if ($options_profile['list_' . $i . '_status'] == 0) continue;
                    $data['list_' . $i] = in_array($i, $_REQUEST['nl']) ? 1 : 0;
                }
            }

            // Profile
            for ($i = 1; $i <= 19; $i++) {
                if ($options_profile['profile_' . $i . '_status'] == 0) continue;
                $data['profile_' . $i] = stripslashes($_REQUEST['np' . $i]);
            }

            // Feed by mail
            $options_feed = get_option('newsletter_feed');
            if ($options_feed['enabled'] == 1 && $options_profile['feed_status'] > 0) {
                if (isset($_POST['feed'])) $data['feed'] = 1;
                else $data['feed'] = 0;
            }

            // Follow up
            $options_followup = get_option('newsletter_followup');
            if ($options_followup['enabled'] == 1 && $options_profile['followup_status'] > 0) {
                if (isset($_POST['followup'])) $data['followup'] = 1;
                else $data['followup'] = 0;
            }

            $wpdb->update($wpdb->prefix . 'newsletter', $data, array('id' => $user->id));
            $url = empty($this->options_main['url']) ? get_option('home') : $this->options_main['url'];
            header('Location: ' . $url . '/?na=pe&ni=' . $user->id . '&nt=' . $user->token);
            die();
        }

        // Profile editing.
        if ($action == 'pe') {
            $options_profile = get_option('newsletter_profile');
            if (empty($options_profile['profile_text'])) $options_profile['profile_text'] = '{profile_form}';
            $this->message = $this->replace($options_profile['profile_text'], $user);
            return;
        }

        if ($action == 'm') {
            if ($user->status == 'C') setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
            header('Location: ' . $this->options_main['lock_url']);
            die();
        }

        // Feed by mail unsubscription
        if ($action == 'eu') {
            $wpdb->query("update " . $wpdb->prefix . "newsletter set feed=2 where id=" . $user->id);
            $options_feed = get_option('newsletter_feed');
            $this->message = $options_feed['unsubscribed_text'];
            return;
        }

        if ($action == 'fu') {
            $wpdb->query("update " . $wpdb->prefix . "newsletter set followup=2 where id=" . $user->id);
            $options_followup = get_option('newsletter_followup');
            $this->message = $options_followup['unsubscribed_text'];
            return;
        }
    }

    function set_limits() {
        global $wpdb;

        $wpdb->query("set session wait_timeout=300");
        // From default-constants.php
        if (function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < 128 )) @ini_set('memory_limit', '128M');
    }

    /**
     * Return a user if there are request parameters or cookie with identification data otherwise null.
     */
    function check_user() {
        global $wpdb;

        $id = (int) $_REQUEST['ni'];
        $token = $_REQUEST['nt'];
        if (empty($id) || empty($token)) list ($id, $token) = @explode('-', $_COOKIE['newsletter'], 2);

        return $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "newsletter where id=%d and token=%s", $id, $token));
    }

    function get_user($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "newsletter where id=%d", $id));
    }

    /**
     * Replace any kind of newsletter placeholder in a text.
     */
    function replace($text, $user=null) {
        global $wpdb;

        if (is_array($user)) $user = $this->get_user($user['id']);
        $text = str_replace('{home_url}', get_option('home'), $text);
        $text = str_replace('{blog_title}', get_option('blogname'), $text);
        $text = str_replace('{blog_description}', get_option('blogdescription'), $text);
        $text = str_replace('{date}', date_i18n(get_option('date_format')), $text);

        // Date processing
        $x = 0;
        while (($x = strpos($text, '{date_', $x)) !== false) {
            $y = strpos($text, '}', $x);
            if ($y === false) continue;
            $f = substr($text, $x+6, $y-$x-6);
            $text = substr($text, 0, $x) . date($f) . substr($text, $y+1);
        }
        
        if ($user != null) {
            $text = str_replace('{email}', $user->email, $text);
            $text = str_replace('{name}', $user->name, $text);
            $text = str_replace('{surname}', $user->surname, $text);
            $text = str_replace('{token}', $user->token, $text);
            $text = str_replace('%7Btoken%7D', $user->token, $text);
            $text = str_replace('{id}', $user->id, $text);
            $text = str_replace('%7Bid%7D', $user->id, $text);
            $text = str_replace('{ip}', $user->ip, $text);

            if (strpos($text, '{profile_form}') !== false) $text = str_replace('{profile_form}', $this->profile_form($user), $text);

            for ($i = 1; $i < 20; $i++) {
                $p = 'profile_' . $i;
                $text = str_replace('{profile_' . $i . '}', $user->$p, $text);
            }

            $profile = $wpdb->get_results("select name,value from " . $wpdb->prefix . "newsletter_profiles where newsletter_id=" . $user->id);
            foreach ($profile as $field) {
                $text = str_ireplace('{np_' . $field->name . '}', htmlspecialchars($field->value), $text);
            }

            $text = preg_replace('/\\{np_.+\}/i', '', $text);

            $base = $this->options_main['url'];
            if ($base == '') $base = get_option('home');
            $id_token = '&amp;ni=' . $user->id . '&amp;nt=' . $user->token;

            $text = $this->replace_url($text, 'SUBSCRIPTION_CONFIRM_URL', $this->add_qs($base, 'na=c' . $id_token));
            $text = $this->replace_url($text, 'UNSUBSCRIPTION_URL', $this->add_qs($base, 'na=u' . $id_token));
            $text = $this->replace_url($text, 'UNSUBSCRIPTION_CONFIRM_URL', $this->add_qs($base, 'na=uc' . $id_token));
            $text = $this->replace_url($text, 'FOLLOWUP_SUBSCRIPTION_URL', $this->add_qs($base, 'na=fs' . $id_token));
            $text = $this->replace_url($text, 'FOLLOWUP_UNSUBSCRIPTION_URL', $this->add_qs($base, 'na=fu' . $id_token));
            $text = $this->replace_url($text, 'FEED_SUBSCRIPTION_URL', $this->add_qs($base, 'na=es' . $id_token));
            $text = $this->replace_url($text, 'FEED_UNSUBSCRIPTION_URL', $this->add_qs($base, 'na=eu' . $id_token));
            $text = $this->replace_url($text, 'PROFILE_URL', $this->add_qs($base, 'na=pe' . $id_token));
            $text = $this->replace_url($text, 'UNLOCK_URL', $this->add_qs($base, 'na=m' . $id_token));

            for ($i = 1; $i <= 9; $i++) {
                $text = $this->replace_url($text, 'LIST_' . $i . '_SUBSCRIPTION_URL', $this->add_qs($base, 'na=ls&amp;nl=' . $i . $id_token));
                $text = $this->replace_url($text, 'LIST_' . $i . '_UNSUBSCRIPTION_URL', $this->add_qs($base, 'na=lu&amp;nl=' . $i . $id_token));
            }
        }
        return $text;
    }

    function replace_url($text, $tag, $url) {
        $home = get_option('home') . '/';
        $tag_lower = strtolower($tag);
        $text = str_replace($home . '{' . $tag_lower . '}', $url, $text);
        $text = str_replace($home . '%7B' . $tag_lower . '%7D', $url, $text);
        $text = str_replace('{' . $tag_lower . '}', $url, $text);
        $text = str_replace('%7B' . $tag_lower . '%7D', $url, $text);

        // for compatibility
        $text = str_replace($home . $tag, $url, $text);

        return $text;
    }

    function add_qs($url, $qs, $amp=true) {
        if (strpos($url, '?') !== false) {
            if ($amp) return $url . '&amp;' . $qs;
            else return $url . '&' . $qs;
        }
        else return $url . '?' . $qs;
    }

    /** Used only by feed by mail theme, relies on $user object variable and on global
     * $post variable.
     */
    function post_is_old() {
        global $post;
        $this->log('User: ' . $this->user->id, 3);
        $this->log('Post GMT date: ' . $post->post_date_gmt, 3);
        $this->log(($this->m2t($post->post_date_gmt) - $this->user->feed_time) . ' (if positive the post is new)', 3);
        return $this->m2t($post->post_date_gmt) <= $this->user->feed_time;
    }

    function hook_shutdown() {
        if ($this->mailer != null) $this->mailer->SmtpClose();
    }

    function shortcode_newsletter_lock($attrs, $content=null) {
        global $hyper_cache_stop, $lite_cache_stop;
        
        $hyper_cache_stop = true;
        $lite_cache_stop = true;

        $user = $this->check_user();
        if ($user != null && $user->status == 'C') {
            return do_shortcode($content);
        }

        $buffer = $this->options_main['lock_message'];
        ob_start();
        eval('?>' . $buffer . "\n");
        $buffer = ob_get_clean();
        $buffer = str_ireplace('<form', '<form method="post" action="' . $this->options_main['url'] . '"', $buffer);
        $buffer = str_ireplace('</form>', '<input type="hidden" name="na" value="s"/></form>', $buffer);
        return do_shortcode($buffer);
    }

    function normalize_email($email) {
        $email = strtolower(trim($email));
        if (!is_email($email)) return null;
        return $email;
    }

    function normalize_name($name) {
        $name = str_replace(';', ' ', $name);
        $name = strip_tags($name);
        return $name;
    }

    function is_email($email, $empty_ok=false) {
        $email = strtolower(trim($email));
        if ($empty_ok && $email == '') return true;

        if (!is_email($email)) return false;
        if (strpos($email, 'mailinator.com') !== false) return false;
        if (strpos($email, 'guerrillamailblock.com') !== false) return false;
        if (strpos($email, 'emailtemporanea.net') !== false) return false;
        return true;
    }

    function m2t($s) {
        $s = explode(' ', $s);
        $d = explode('-', $s[0]);
        $t = explode(':', $s[1]);
        return gmmktime((int) $t[0], (int) $t[1], (int) $t[2], (int) $d[1], (int) $d[2], (int) $d[0]);
    }

    function query($query) {
        global $wpdb;

        $this->log($query, 3);
        return $wpdb->query($query);
    }

    function notify_admin($user, $subject) {
        if ($this->options_main['notify'] != 1) return;
        $message = "Subscriber details:\n\n" .
            "email: " . $user->email . "\n" .
            "first name: " . $user->name . "\n" .
            "last name: " . $user->surname . "\n" .
            "gender: " . $user->sex . "\n";

        $options_profile = get_option('newsletter_profile');

        for ($i=0; $i<NEWSLETTER_PROFILE_MAX; $i++) {
            if ($options_profile['profile_' . $i] == '') continue;
            $field = 'profile_' . $i;
            $message .= $options_profile['profile_' . $i] . ': ' . $user->$field . "\n";
        }

        $message .= "token: " . $user->token . "\n" .
            "status: " . $user->status . "\n" .
            "\nYours, Newsletter Pro.";

        wp_mail(get_option('admin_email'), '[' . get_option('blogname') . '] ' . $subject, $message, "Content-type: text/plain; charset=UTF-8\n");
    }

    /**
     * Adds a new user to the subscriber table. $user must be an associative array with
     * matching keys to the database columns and with already validated fields
     * email   -> user email address (MANDATORY)
     * name    -> first name or full name
     * surname -> last name
     * sex     -> m, f or n (male, female or none - lowercase)
     * status  -> S or C (MANDATORY) (S: subscribed but not confirmed, C: confirmed - see below about notifications)
     * 
     * If the user is already subscribed (email present) nothing happens.
     * If $notifications == true, welcome or activation request emails are sent to the user email
     * address.
     * Administrative notifications are NOT sent.
     * 
     */
    function add_user($user, $notifications=false) {
        $u = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix . "newsletter where email=%s", $user['email']));
        if ($u == null) {

            $user['token'] = md5(rand());

            $wpdb->insert($wpdb->prefix . 'newsletter', $user);
            $user = $this->get_user($wpdb->insert_id); // back to an object
        }

        if ($notifications) {
            $options = get_option('newsletter', array());

            $prefix = ($user->status == 'C') ? 'confirmed_' : 'confirmation_';
            $message = $options[$prefix . 'message'];
            $subject = $options[$prefix . 'subject'];

            $this->mail($user->email, $this->replace($subject, $user), $this->replace($message, $user));
        }
    }
}

require_once(dirname(__FILE__) . '/widget.php');

/**
 * Intercept the request parameters which drive the subscription and unsubscription
 * process.
 */
function newsletter_init() {
    global $newsletter_step, $wpdb, $newsletter_subscriber;
    global $hyper_cache_stop, $lite_cache_stop, $newsletter_options_main;
    return;
    // "na" always is the action to be performed - stands for "newsletter action"
    $action = $_REQUEST['na'];
    if (!$action) return;

    $hyper_cache_stop = true;
    $lite_cache_stop = true;

    // Subscription options (texts and emails)
    $options = get_option('newsletter');

    // POP-UP
    if ($action == 'pu-f') {
        echo newsletter_form($newsletter_options_main['popup_form'], 'popup_form', true);
        die();
    }




    // Unsubscription
    // User confirmed he want to unsubscribe clicking the link on unsubscription
    // page
    //--------------------------------------------------------------------------
    // Follow up subscription
    if ($action == 'fs') {
        newsletter_followup_subscribe($_REQUEST['ni'], $_REQUEST['nt']);
        newsletter_die('Follow up subscription successful. <a href="' . get_option('home') . '">Go to home page</a>');
        $newsletter_step = 'followup_subscribed';
        return;
    }


    // Feed subscription/unbsubscription
    if ($action == 'es') {
        newsletter_feed_subscribe($_REQUEST['ni'], $_REQUEST['nt']);
        newsletter_die('Feed subscription successful. <a href="' . get_option('home') . '">Go to home page</a>');
        $newsletter_step = 'feed_subscribed';
        return;
    }

    // Lists subscription and unsubscription
    if ($action == 'ls') {
        newsletter_list_subscribe($_REQUEST['ni'], $_REQUEST['nt'], $_REQUEST['nl']);
        newsletter_die('List subscription successful. <a href="' . get_option('home') . '">Go to home page</a>');
        $newsletter_step = 'followup_subscribed';
    }

    if ($action == 'lu') {
        newsletter_list_unsubscribe($_REQUEST['ni'], $_REQUEST['nt'], $_REQUEST['nl']);
        newsletter_die('List unsubscription successful. <a href="' . get_option('home') . '">Go to home page</a>');
        $newsletter_step = 'followup_unsubscribed';
    }
}

/**
 * Find an image for a post checking the media uploaded for the post and
 * choosing the first image found.
 */
function nt_post_image($post_id, $size='thumbnail', $alternative=null) {

    $attachments = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

    if (empty($attachments)) {
        return $alternative;
    }

    foreach ($attachments as $id => $attachment) {
        $image = wp_get_attachment_image_src($id, $size);
        return $image[0];
    }
    return null;
}

function nt_option($name, $def = null) {
//    if ($newsletter_is_feed && $name == 'posts') {
//        $options = get_option('newsletter_feed');
//        return $options['posts'];
//    }
    $options = get_option('newsletter_email');
    $option = $options['theme_' . $name];
    if (!isset($option)) return $def;
    else return $option;
}

// For compatibility
function newsletter_form($number=null) {
    global $newsletter;
    echo $newsletter->form($number);
}

function newsletter_pro_popup()
{
    global $newsletter;

    $options = get_option('newsletter');
    $options_profile = get_option('newsletter_profile');
    $form .= '<div class="newsletter newsletter-popup">';
    $form .= '<form action="' . $newsletter->options_main['url'] . '" method="post">';
    $form .= '<input type="hidden" name="na" value="os"/>';
    $form .= '<input type="hidden" name="nr" value="popup"/>';

    if ($options_profile['name_status'] == 2)
        $form .= '<p><input class="newsletter-firstname" type="text" name="nn" value="' . $options_profile['name'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';

    if ($options_profile['surname_status'] == 2)
        $form .= '<p><input class="newsletter-lastname" type="text" name="ns" value="' . $options_profile['surname'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';

    $form .= '<p><input class="newsletter-email" type="text" name="ne" value="' . $options_profile['email'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';

    if ($options_profile['sex_status'] == 2) {
        $form .= '<p><select name="nx" class="newsletter-sex">';
        $form .= '<option value="m">' . $options_profile['sex_male'] . '</option>';
        $form .= '<option value="f">' . $options_profile['sex_female'] . '</option>';
        $form .= '</select></p>';
    }

    // Extra profile fields
    for ($i = 1; $i <= 19; $i++) {
        if ($options_profile['profile_' . $i . '_status'] != 2) continue;
        if ($options_profile['profile_' . $i . '_type'] == 'text') {
            $form .= '<p><input class="newsletter-profile newsletter-profile-' . $i . '" type="text" name="np' . $i . '" value="' . $options_profile['profile_' . $i] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';
        }
        if ($options_profile['profile_' . $i . '_type'] == 'select') {
            $form .= '<p>' . $options_profile['profile_' . $i] . '<br /><select class="newsletter-profile newsletter-profile-' . $i . '" name="np' . $i . '">';
            $opts = explode(',', $options_profile['profile_' . $i . '_options']);
            for ($i = 0; $i < count($opts); $i++) {
                $form .= '<option>' . trim($opts[$i]) . '</option>';
            }
            $form .= '</select></p>';
        }
    }

    $lists = '';
    for ($i = 1; $i <= 9; $i++) {
        if ($options_profile['list_' . $i . '_status'] != 2) continue;
        $lists .= '<input type="checkbox" name="nl[]" value="' . $i . '"/>&nbsp;' . $options_profile['list_' . $i] . '<br />';
    }
    if (!empty($lists)) $form .= '<p>' . $lists . '</p>';
    return $form;
}