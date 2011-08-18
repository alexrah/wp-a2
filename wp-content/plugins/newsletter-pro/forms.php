<?php
@include_once 'commons.php';

$options = get_option('newsletter_forms');

if (isset($_POST['save'])) {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_forms', $options);
}
?>

<div class="wrap">

    <h2><?php _e('Newsletter Forms', 'newsletter'); ?></h2>
    <p>
        Version 2.1 note: with the new widget and the new configuration options on subscription
        panel, custom forms can be created directly while setting up the subscription page and
        the widget.
    </p>

<p>
    Newsletter is provided with a number of subscription forms for widget,
    subscription page and embedded subscription form. Here you can define alternative
    coding them in HTML.
</p>
<p>
    There are 10 alternative forms available, numbered from 1 to 10. Use
    the form number every time it can be specified in configurations, short codes
    and so on.
</p>
<p>
    Form "action" need to be set to "{newsletter_url}", see examples on the bottom.
</p>
<p>
    Forms can be called with PHP code, as well, in this way:
</p>
<p>
    <code>&lt;?php newsletter_form(1); ?&gt;</code>
</p>
<p>
    where "1" is the form number (so it can be from 1 to 10). The form will be echoed.
</p>
<p>
    If you cannot call PHP code where you need to insert a form, you can do it manually
    using directly the HTML for code BUT replacing (by hand) the {newsletter_url} tag
    with the real URL of your subscription page.
</p>

<form method="post" action="">

    <table class="form-table">
        <?php for ($i=1; $i<=10; $i++) { ?>
        <tr valign="top">
            <th>Form <?php echo $i; ?></th>
            <td>
                <textarea cols="70" width="100%" style="width:100%;font-family:monospace" rows="7" wrap="off" name="options[form_<?php echo $i; ?>]"><?php echo htmlspecialchars($options['form_' . $i])?></textarea>
                <br />
                <input class="button" type="submit" name="save" value="Save"/>
            </td>
        </tr>
            <?php } ?>
    </table>

    <h3>Examples</h3>
    <p>Those are examples of forms, you can copy and paste that code as starting point.</p>

    <p><strong>Simple standard form</strong></p>
    <pre style="font-family:monospace"><?php echo htmlspecialchars(
        '<form method="post" action="{newsletter_url}" style="text-align: center">
    <input type="hidden" name="na" value="s"/>
    <table cellspacing="3" cellpadding="3" border="0" width="50%">
        <tr><td>Your name</td><td><input type="text" name="nn" size="30"/></td></tr>
        <tr><td>Your email</td><td><input type="text" name="ne" size="30"/></td></tr>
        <tr><td colspan="2" style="text-align: center"><input type="submit" value="Subscribe me"/></td></tr>
    </table>
</form>'
        ); ?></pre>

    <p><strong>Form asking "sex"</strong></p>
    <pre style="font-family:monospace"><?php echo htmlspecialchars(
        '<form method="post" action="{newsletter_url}" style="text-align: center">
    <input type="hidden" name="na" value="s"/>
    <table cellspacing="3" cellpadding="3" border="0" width="50%">
        <tr><td>Your name</td><td><input type="text" name="nn" size="30"/></td></tr>
        <tr><td>Your email</td><td><input type="text" name="ne" size="30"/></td></tr>
        <tr><td>You are</td><td><select name="np[sex]"><option value="M">Male</option><option value="F">Female</option></select></td></tr>
        <tr><td colspan="2" style="text-align: center"><input type="submit" value="Subscribe me"/></td></tr>
    </table>
</form>'
        ); ?></pre>

    <p><strong>Widget form collecting "sex" field</strong></p>
    <pre style="font-family:monospace"><?php echo htmlspecialchars(
        '<form action="{newsletter_url}" method="post">
{text}
    <p><input type="text" name="nn" value="Il tuo nome" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
    <p><input type="text" name="ne" value="La tua email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
    <p><select name="np[sex]"><option value="M">Maschio</option><option value="F">Femmina</option></select></p>
    <p><input type="submit" value="Avanti -&gt;"/></p>
<input type="hidden" name="na" value="s"/>
</form>'
        ); ?></pre>

</form>


</div>