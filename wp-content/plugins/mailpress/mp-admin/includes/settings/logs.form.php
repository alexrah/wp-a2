<?php
if (!isset($logs)) $logs = get_option(MailPress::option_name_logs);

$formname = substr(basename(__FILE__), 0, -4); 
?>
<form name='<?php echo $formname ?>' action='' method='post' class='mp_settings'>
	<input type='hidden' name='formname' value='<?php echo $formname ?>' />
	<table class='form-table'>
<?php MP_AdminPage::logs_sub_form('general', $logs, __('Mails', MP_TXTDOM) , __('Mailing log', MP_TXTDOM) , __('(for <b>ALL</b> mails send through MailPress)', MP_TXTDOM), __('Number of Log files : ', MP_TXTDOM)); ?>
<?php do_action('MailPress_settings_logs', $logs); ?>
	</table>
<?php MP_AdminPage::save_button(); ?>
</form>