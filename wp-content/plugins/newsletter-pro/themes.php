<?php
@include_once 'commons.php';

$nc = new NewsletterControls();

if (!$nc->is_action()) {
    $nc->data = get_option('newsletter_themes');
}
else {
    if ($nc->is_action('save')) {
        update_option('newsletter_themes', $nc->data);
    }
}
?>

<div class="wrap">
    
    <h2>Newsletter Themes</h2>
    <p>
       This panel IS obsolete.
    </p>


    <form method="post" action="#">
        <?php $nc->init(); ?>

        <?php for ($i=1; $i<=9; $i++) { ?>
        <h3>Theme <?php echo $i; ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th>Theme name</th>
                <td>
                    <?php $nc->text('name_' . $i); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <?php $nc->textarea('theme_' . $i); ?>
                </td>
            </tr>
        </table>
        <p><?php $nc->button('save', 'Save'); ?></p>
        <?php } ?>
        
    </form>
</div>

