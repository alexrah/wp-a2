<?php
@include_once 'commons.php';

$nc = new NewsletterControls();

if (!$nc->is_action()) {
    $nc->data = get_option('newsletter_feed');
}
else {

    if ($nc->is_action('now')) {
        if ($nc->data['enabled'] == 1) {
            delete_transient('newsletter_feed');
            $newsletter->hook_newsletter_feed(true);
        }
    }

    if ($nc->is_action('save')) {
        $nc->data = stripslashes_deep($_POST['options']);
        update_option('newsletter_feed', $nc->data);

        wp_clear_scheduled_hook('newsletter_feed');

        // Create the daily event at the specified time
        if ($nc->data['enabled'] == 1) {
            $hour = (int)$nc->data['hour'] - get_option('gmt_offset'); // to gmt
            $time = gmmktime($hour, 0, 0, gmdate("m"), gmdate("d")+1, gmdate("Y"));
            wp_schedule_event($time, 'daily', 'newsletter_feed');
        }
    }

    if ($nc->is_action('test')) {
        $users = newsletter_get_test_subscribers();
        foreach ($users as $user) {
            $user->feed_time = time() - $nc->data['test_days']*3600*24;
        }
        $email = new stdClass();
        $email->message = '';
        $email->theme = $nc->data['theme'];
        $email->subject = 'Test feed';
        $email->type = 'feed';
        $email->id = 0;
        $newsletter->feed_send($email, $users);
    }

    if ($nc->is_action('delete')) {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter_emails where id=" . $_POST['btn']);
    }

    if ($nc->is_action('reset_time')) {
        delete_option('newsletter_feed_last_time');
    }
    if ($nc->is_action('back_time')) {
        $time = get_option('newsletter_feed_last_time', 0) - 3600*24;
        update_option('newsletter_feed_last_time', $time);
    }
}

$nc->errors($errors);
$nc->errors($messages);
?>
<div class="wrap">

    <h2>Newsletter Feed by Mail</h2>
    <p>
        Here you can configure a feed by mail service. Feed by mail is an automated mailing service which
        sends a mail on a planned hour and day of week with an excerpt of the last blog posts (or the new
        posts after the previous mail).
    </p>

    <p>
        Newsletter subscribers need to be activated to receive this kind of mails. You can set to subscribe to this
        service every new user and, on subscribers panel, manage such subscription. On that panel you can also subscribe
        to feed by mail any user.
    </p>

<form method="post" action="">
    <?php $nc->init(); ?>

        <h3>Configuration</h3>


    <table class="form-table">
        <tr valign="top">
            <th>Enabled?</th>
            <td>
                <?php $nc->yesno('enabled'); ?>
            </td>
        </tr>

        <tr valign="top">
            <th>Days</th>
            <td>
                Monday&nbsp;<?php $nc->yesno('day_1'); ?>
                Tuesday&nbsp;<?php $nc->yesno('day_2'); ?>
                Wednesday&nbsp;<?php $nc->yesno('day_3'); ?>
                Thursday&nbsp;<?php $nc->yesno('day_4'); ?>
                Friday&nbsp;<?php $nc->yesno('day_5'); ?>
                Saturday&nbsp;<?php $nc->yesno('day_6'); ?>
                Sunday&nbsp;<?php $nc->yesno('day_7'); ?>
            </td>
        </tr>

        <tr valign="top">
            <th>Delivery hour</th>
            <td>
                <?php $nc->hours('hour'); ?>
            </td>
        </tr>

        <tr valign="top">
            <th>Track link clicks?</th>
            <td>
                <?php $nc->yesno('track'); ?>
            </td>
        </tr>

        <tr>
            <th>Add this service to every new subscriber?</th>
            <td>
                <?php $nc->yesno('add_new'); ?>
                <div class="hints">
                    This setting is valid even if the service is disabled, so users that subscribe will be added to the feed by mail service
                    but no feed by mail email will be sent.
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th>Subject</th>
            <td>
                <?php $nc->text('subject', 50); ?>
                <div class="hints">
                    The subject of emails sent. If you leave it empty, the last post title is used. You can use the Newsletter tags.
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th>Theme</th>
            <td>
                <?php $nc->feed_themes('theme'); ?>
                <div class="hints">
                    Send a test to see the theme layout. Custom themes are stored on wp-content/plugins/newsletter-custom/themes-feed.
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th>Extra content</th>
            <td>
                <?php $nc->textarea('extra'); ?>
                <div class="hints">
                    This text, named "extra", can be used by feed theme to fill a specific mail area, like a
                    sidebar. The use DEPENDS on the theme you are using. 
                </div>
            </td>
        </tr>
        <?php /*
        <tr valign="top">
            <th>Email theme</th>
            <td>
                <?php $nc->textarea('theme'); ?>
                <div class="hints">
                    This theme is used to compose HTML emails created by the feed by mail sub-system. This theme is
                    complex ad fully generate the email body for each user. There is some PHP code used to cycle the latest posts and to
                    control whenever a post has been already sent or not.<br />
                    I tried
                    to make it as simple as possible, so there is chances you can modify the opening and the ending of the message
                    without risks. A programmer can customize this theme without limits!<br />
                    <strong>To restore the original value, empty the text area and save.</strong>
                </div>
            </td>
        </tr>
        */ ?>

        <tr valign="top">
            <th>Good bye message</th>
            <td>
                <?php $nc->textarea('unsubscribed_text'); ?>
                <div class="hints">
                    Message shown when a user decide to cancel the feed by mail service, clicking on the
                    link generated by {feed_unsubscription_url}.
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th>Test configurations</th>
            <td>
                post older than <?php $nc->select('test_days', array('1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '10'=>10, '15'=>15, '20'=>20, '25'=>25, '30'=>30)); ?> day(s) ago are considered already sent
                <div class="hints">
                    That option let you to test themes changing the number of post considered new to see how the email will
                    look with a variable amount of new or old posts. See the feed-1 theme implementation for more details
                    on how to deal with new and old posts.
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th>New posts last check time and posts on queue</th>
            <td>
                <?php echo newsletter_date(get_option('newsletter_feed_last_time')); ?>
                <?php $nc->button_confirm('reset_time', 'Reset it', 'Are you sure?'); ?>
                <?php $nc->button('back_time', 'Back one day'); ?>
                <div class="hints">
                    Even if you reset the global last check time (or change it), users who have already received an update kept their
                    last personal update time.
                </div>
                
                <?php
                $posts = new WP_Query();
                $posts->query(array('showposts'=>20, 'post_status'=>'publish'));

                while ($posts->have_posts())
                {
                    $posts->the_post();
                    if (mysql2date('G', $post->post_date_gmt) <= get_option('newsletter_feed_last_time')) break;
                ?>
                [<?php echo the_ID(); ?>] <?php echo newsletter_date($newsletter->m2t($post->post_date_gmt)); ?> <a target="_blank" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a><br />
                <?php } ?>
            </td>
        </tr>
    </table>

    <p class="submit">
        <?php $nc->button('save', 'Save'); ?>
        <?php $nc->button('test', 'Test'); ?>
        <?php $nc->button('now', 'Send now!'); ?>
    </p>


<?php
$emails = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter_emails where type='feed' order by id desc");
?>
    <h3>Emails</h3>
    <p>Delivery engine next run: <?php echo wp_next_scheduled('newsletter')-time(); ?> seconds</p>
    <table class="widefat">
        <thead>
            <tr>
                <th>Id</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($emails as &$email) { ?>
            <tr>
                <td><?php echo $email->id; ?></td>
                <td><a href="admin.php?page=newsletter-pro/feed-edit.php&amp;id=<?php echo $email->id; ?>"><?php echo htmlspecialchars($email->subject); ?></a></td>
                <td><?php echo $email->date; ?></td>
                <td>
                    <?php echo $email->status; ?>
                    (<?php echo $email->sent; ?>/<?php echo $email->total; ?>)
                </td>
                <td><a href="admin.php?page=newsletter-pro/emails-stats.php&amp;id=<?php echo $email->id; ?>">statistics</a></td>
                <td><?php $nc->button_confirm('delete', 'Delete', 'Are you sure?', $email->id); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>

    
</div>