<?php
@include_once 'commons.php';
$nc = new NewsletterControls();

if (isset($_GET['id'])) {
    $nc->load($wpdb->prefix . 'newsletter_emails', $_GET['id']);
}
else {
    if ($nc->is_action('save') || $nc->is_action('send')) {
        $nc->save($wpdb->prefix . 'newsletter_emails');
    }

    if ($nc->is_action('pause')) {
        $nc->update($wpdb->prefix . 'newsletter_emails', 'status', 'paused');
    }

    if ($nc->is_action('continue')) {
        $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set status='sending' where id=" . $nc->data['id']);
    }

    if ($nc->is_action('abort')) {
        $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set last_id=0, status='new' where id=" . $nc->data['id']);
    }

    if ($nc->is_action('delete')) {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter_emails where id=" . $nc->data['id']);
        ?><script>location.href="admin.php?page=newsletter-pro/feed.php";</script><?php
        return;
    }

    $nc->load($wpdb->prefix . 'newsletter_emails', $nc->data['id']);
}

$nc->errors($errors);
$nc->messages($messages);
?>

<div class="wrap">

    <h2>Feed Email</h2>

    <form method="post" action="admin.php?page=newsletter-pro/feed-edit.php">
        <?php $nc->init(); ?>
        <?php $nc->hidden('id'); ?>

        <table class="form-table">
            <tr valign="top">
                <th></th>
                <td>
                    status: <?php $nc->value('status'); ?> (<?php $nc->value('sent'); ?>/<?php $nc->value('total'); ?>)
                    id: <?php $nc->value('id'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Track message links?</th>
                <td>
                    <?php $nc->yesno('track'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th>Subject</th>
                <td>
                    <?php $nc->text('subject', 70); ?>
                    <br />
                    Tags: <strong>{name}</strong> receiver name.
                </td>
            </tr>

            <tr valign="top">
                <th>Message</th>
                <td>
                    There is no message since they are generated per user from the feed theme.
                </td>
            </tr>

            <tr valign="top">
                <th>Theme</th>
                <td>
                    <?php $nc->feed_themes('theme'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php if ($nc->data['status'] != 'sending') $nc->button('save', 'Save'); ?>
            <?php if ($nc->data['status'] == 'sending') $nc->button_confirm('pause', 'Pause', 'Pause the delivery?'); ?>
            <?php if ($nc->data['status'] == 'paused') $nc->button_confirm('continue', 'Continue', 'Continue the delivery?'); ?>
            <?php if ($nc->data['status'] != 'new') $nc->button_confirm('abort', 'Abort', 'Abort the delivery?'); ?>
            <?php $nc->button_confirm('delete', 'Delete', 'Delete?'); ?>
        </p>

    </form>
</div>
