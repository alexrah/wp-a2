<?php
@include_once 'commons.php';

$nc = new NewsletterControls();

if (!$nc->is_action()) {
    $nc->data = get_option('newsletter_followup', array());
}
else {
    if ($nc->is_action('save')) {

        if ($nc->data['enabled'] == 1) {
            $nc->data['interval'] = (int)$nc->data['interval'];
            if ($nc->data['interval'] <= 0) $errors = 'Interval must be greater that zero.';
        }

        if ($errors == null) {
            $nc->data['steps'] = 0;
            for ($i=1; $i<=NEWSLETTER_FOLLOWUP_MAX_STEPS; $i++) {
                $nc->data['step_' . $i . '_subject'] = trim($nc->data['step_' . $i . '_subject']);
                if (empty($nc->data['step_' . $i . '_subject'])) break;
                $nc->data['steps']++;
            }

            update_option('newsletter_followup', $nc->data);
        }
    }

    if ($nc->is_action('test')) {
        $nc->data = stripslashes_deep($_POST['options']);
        $users = newsletter_get_test_subscribers();
        foreach ($users as $user) {
            $user->followup_step = $nc->data['test_step']-1;
        }
        $newsletter->followup_send($users, $nc->data);
    }
}

$nc->errors($errors);
$nc->errors($messages);
?>

<div class="wrap">

    <h2>Newsletter: Follow Up/Autoresponder</h2>
    <p>
        In this panel you can set up a series od messages to be automatically sent to new subscribers.
        The use if left to your	fantasy. Two examples: you can set up a set of "lessons" about topics
        covered in your blog (may be lessons you promised to all who subscriber your newsletter)
        or a set of selling letters for your products.</p>


<?php if ($nc->data['novisual'] != 1) { ?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter-pro/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "visual",
        theme : "advanced",
        theme_advanced_disable : "styleselect",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location : "top",
        document_base_url : "<?php echo get_option('home'); ?>/",
        content_css : "<?php echo get_option('blogurl'); ?>/wp-content/plugins/newsletter-pro/editor.css?" + new Date().getTime()
    });

    function newsletter_test(f, i)
    {
        f.elements['options[test_step]'].value = i;
        f.submit();
    }
</script>
<?php } ?>

<p><strong>The message sequence stops on first message with empty subject.</strong></p>

<form action="" method="post">
    <?php $nc->init(); ?>
    <?php $nc->hidden('test_step'); ?>
    
    <h3>Configuration</h3>
    <table class="form-table">
        <tr>
            <th>Enabled?</th>
            <td><?php $nc->yesno('enabled'); ?>
                <div class="hints">
                    When disabled, the sequence stops but any subscriber who is signed
                    up keep his own status and when re-enabled the sequence restarts.
                </div>
            </td>
        </tr>
        <tr>
            <th>Interval between steps</th>
            <td>
                <?php $nc->text('interval', 5); ?> (hours)
                <div class="hints">
                    Choose how much hours to wait before sending the next message in
                    the sequence. Usually 24/48 hours is a good choice.
                </div>
            </td>
        </tr>
        <tr>
            <th>Add to follow up every new subscriber?</th>
            <td>
                <?php $nc->yesno('add_new'); ?>
                <div class="hints">
                    Set to "yes" if you want to start a follow up sequence for each new subscribers.
                    If subscribers signed up because you promised a set of lessons,
                    be sure to set to "yes".
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th>Track link clicks?</th>
            <td>
                <?php $nc->yesno('track'); ?>
                <div class="hints">
                    Setting to "yes", as for normal newsletter, every link in follow up messages
                    are rewritten and tracked. You will find statistics of follow up
                    link clicks on statistics
                    panel.
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th>Disable visual editors?<br/><small>Only for follow up messages only</small></th>
            <td><?php $nc->yesno('novisual'); ?></td>
        </tr>
        <tr>
            <th>Messages theme</th>
            <td>
                <?php $nc->followup_themes('theme'); ?>
                <div class="hints">
                    See the user guide to create your own theme.
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th>Good bye message</th>
            <td>
                <?php $nc->editor('unsubscribed_text'); ?>
                <div class="hints">
                    Message shown when a user decide to cancel the follow up service, clicking on the
                    link generated by {followup_unsubscription_url} (add it on theme above).
                </div>
            </td>
        </tr>
    </table> 
    <p class="submit"><?php $nc->button('save', 'Save'); ?></p>

    <?php for ($i=1; $i<=9; $i++) { ?>
    <table class="form-table">
        <tr>
            <th>Message <?php echo $i; ?><br />
                <a href="admin.php?page=newsletter-pro/emails-stats.php&amp;id=f<?php echo $i; ?>">Statistics</a></th>
            <td><?php $nc->email('step_' . $i); ?></td>
        </tr>
    </table>
    <p class="submit"><?php $nc->button('save', 'Save'); ?> <?php $nc->button('test', 'Test', 'newsletter_test(this.form,' . $i . ')'); ?> </p>
    <?php } ?>
</form>
    
</div>