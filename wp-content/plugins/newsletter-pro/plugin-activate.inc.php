<?php
global $charset_collate;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$version = get_option('newsletter_pro_version', 0);

$sql = "CREATE TABLE `" . $wpdb->prefix . "newsletter` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL DEFAULT '',
      `surname` varchar(100) NOT NULL DEFAULT '',
      `email` varchar(100) NOT NULL DEFAULT '',
      `sex` char(1) NOT NULL DEFAULT 'n',
      `token` varchar(50) NOT NULL DEFAULT '',
      `status` varchar(1) NOT NULL DEFAULT 'S',
      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `followup_time` bigint(20) NOT NULL DEFAULT '0',
      `followup_step` tinyint(4) NOT NULL DEFAULT '0',
      `followup` tinyint(4) NOT NULL DEFAULT '0',
      `feed` tinyint(4) NOT NULL DEFAULT '0',
      `feed_time` bigint(20) NOT NULL DEFAULT '0',
      `list_1` tinyint(4) NOT NULL DEFAULT '0',
      `list_2` tinyint(4) NOT NULL DEFAULT '0',
      `list_3` tinyint(4) NOT NULL DEFAULT '0',
      `list_4` tinyint(4) NOT NULL DEFAULT '0',
      `list_5` tinyint(4) NOT NULL DEFAULT '0',
      `list_6` tinyint(4) NOT NULL DEFAULT '0',
      `list_7` tinyint(4) NOT NULL DEFAULT '0',
      `list_8` tinyint(4) NOT NULL DEFAULT '0',
      `list_9` tinyint(4) NOT NULL DEFAULT '0',
      `profile_1` varchar(255) NOT NULL DEFAULT '',
      `profile_2` varchar(255) NOT NULL DEFAULT '',
      `profile_3` varchar(255) NOT NULL DEFAULT '',
      `profile_4` varchar(255) NOT NULL DEFAULT '',
      `profile_5` varchar(255) NOT NULL DEFAULT '',
      `profile_6` varchar(255) NOT NULL DEFAULT '',
      `profile_7` varchar(255) NOT NULL DEFAULT '',
      `profile_8` varchar(255) NOT NULL DEFAULT '',
      `profile_9` varchar(255) NOT NULL DEFAULT '',
      `profile_10` varchar(255) NOT NULL DEFAULT '',
      `profile_11` varchar(255) NOT NULL DEFAULT '',
      `profile_12` varchar(255) NOT NULL DEFAULT '',
      `profile_13` varchar(255) NOT NULL DEFAULT '',
      `profile_14` varchar(255) NOT NULL DEFAULT '',
      `profile_15` varchar(255) NOT NULL DEFAULT '',
      `profile_16` varchar(255) NOT NULL DEFAULT '',
      `profile_17` varchar(255) NOT NULL DEFAULT '',
      `profile_18` varchar(255) NOT NULL DEFAULT '',
      `profile_19` varchar(255) NOT NULL DEFAULT '',
      `referrer` varchar(50) NOT NULL DEFAULT '',
      `ip` varchar(50) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`)
    ) $charset_collate;";

dbDelta($sql);

$sql = "alter table " . $wpdb->prefix . "newsletter CONVERT TO CHARACTER SET utf8";
@$wpdb->query($sql);

$sql = 'drop table if exists ' . $wpdb->prefix . 'newsletter_work';
@$wpdb->query($sql);


// NEWSLETTER_PROFILES
$sql = 'create table if not exists ' . $wpdb->prefix . 'newsletter_profiles (
        `newsletter_id` int NOT NULL,
        `name` varchar (100) NOT NULL DEFAULT \'\',
        `value` text,
        primary key (newsletter_id, name)
        ) DEFAULT charset=utf8';
@$wpdb->query($sql);

$sql = "alter table " . $wpdb->prefix . "newsletter_profiles CONVERT TO CHARACTER SET utf8";
@$wpdb->query($sql);

// NEWSLETTER_EMAILS
$sql = "CREATE TABLE " . $wpdb->prefix . "newsletter_emails (
        `id` int auto_increment,
        `subject` varchar(255) NOT NULL DEFAULT '',
        `message` text,
        `name` varchar(255) NOT NULL DEFAULT '',
        `subject2` varchar(255) NOT NULL DEFAULT '',
        `message2` text,
        `name2` varchar(255) NOT NULL DEFAULT '',
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `status` enum('new','sending','sent','paused') NOT NULL DEFAULT 'new',
        `total` int NOT NULL DEFAULT 0,
        `last_id` int NOT NULL DEFAULT 0,
        `sent` int NOT NULL DEFAULT 0,
        `track` int NOT NULL DEFAULT 0,
        `list` int NOT NULL DEFAULT 0,
        `type` enum('email','feed','followup') NOT NULL DEFAULT 'email',
        `query` varchar(255) NOT NULL DEFAULT '',
        `editor` tinyint NOT NULL DEFAULT 0,
        `sex` char(1) NOT NULL DEFAULT '',
        `theme` varchar(50) NOT NULL DEFAULT '',
        PRIMARY KEY (id)
        ) $charset_collate;";

dbDelta($sql);


$sql = "alter table " . $wpdb->prefix . "newsletter_emails CONVERT TO CHARACTER SET utf8";
@$wpdb->query($sql);


// NEWSLETTER_STATS
$sql = "CREATE TABLE " . $wpdb->prefix . "newsletter_stats (
        `id` int auto_increment,
        `newsletter_id` int NOT NULL DEFAULT 0,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `url` varchar(255) NOT NULL DEFAULT '',
        `newsletter` varchar(50) NOT NULL DEFAULT '',
        `anchor` varchar(200) NOT NULL DEFAULT '',
        `email_id` varchar(10) NOT NULL DEFAULT 0,
        primary key (id)
        ) $charset_collate;";
dbDelta($sql);

// Load DEFAULT options (language specific)
include dirname(__FILE__) . '/languages/en_US.php';
@include dirname(__FILE__) . '/languages/' . WPLANG . '.php';


// MAIN OPTIONS
$options = get_option('newsletter_main', array());

if ($version < 250) {
    // Migration of SMTP configuration
    if (!isset($options['smtp_host'])) {
        $smtp = get_option('newsletter_smtp', array());
        $options['smtp_enabled'] = $smtp['enabled'];
        $options['smtp_host'] = $smtp['host_1'];
        $options['smtp_port'] = $smtp['port_1'];
        $options['smtp_user'] = $smtp['user_1'];
        $options['smtp_pass'] = $smtp['pass_1'];
        delete_option('newsletter_smtp');
    }

    // Migration of "protect" configuration
    if (!isset($options['lock_url'])) {
        $protect = get_option('newsletter_protect', array());
        $options['lock_message'] = $protect['message'];
        $options['lock_url'] = $protect['url'];
        delete_option('newsletter_protect');
    }
}
if (empty($options['theme'])) $options['theme'] = $defaults_main['theme'];
if (empty($options['sender_email'])) $options['sender_email'] = $defaults_main['sender_email'];


update_option('newsletter_main', array_merge($defaults_main, $options));


// SUBSCRIPTION OPTIONS
update_option('newsletter', array_merge($defaults, get_option('newsletter', array())));

// FOLLOW UP OPTIONS
update_option('newsletter_followup', array_merge($defaults_followup, get_option('newsletter_followup', array())));

// EVENTS (do not touch the newsletter_feed event)
wp_clear_scheduled_hook('newsletter_feed_hook');
wp_clear_scheduled_hook('newsletter_feed_job');
wp_clear_scheduled_hook('newsletter');
wp_schedule_event(time() + 30, 'newsletter', 'newsletter');


$options = get_option('newsletter_profile', array());
if (empty($options)) {
    $lists = get_option('newsletter_lists', array());
    for ($i=1; $i<=9; $i++) {
        $options['list_' . $i] = $lists['name_' . $i];
        $options['list_type_' . $i] = $lists['type_' . $i];
    }
}
add_option('newsletter_profile', array(), '', 'no');
update_option('newsletter_profile', array_merge($defaults_profile, array_filter($options)));

// FEED OPTIONS
$options_feed = get_option('newsletter_feed', array());
$options_feed = array_merge($defaults_feed, $options_feed);
update_option('newsletter_feed', $options_feed);

// Activate the feed scheduler at the right hour
wp_clear_scheduled_hook('newsletter_feed');
if ($options_feed['enabled'] == 1) {
    $hour = (int)$options_feed['hour'] - get_option('gmt_offset'); // to gmt
    $time = gmmktime($hour, 0, 0, gmdate("m"), gmdate("d")+1, gmdate("Y"));
    wp_schedule_event($time, 'daily', 'newsletter_feed');
}

// OLD
delete_option('newsletter_feed_batch');
delete_option('newsletter_batch');
$sql = "update " . $wpdb->prefix . "options set autoload='no' where option_name like 'newsletter%'";
@$wpdb->query($sql);

// Update the version number
add_option('newsletter_pro_version', Newsletter::VERSION, '', 'no');
update_option('newsletter_pro_version', Newsletter::VERSION);