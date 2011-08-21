<?php
$mp_general['tab'] = 'subscriptions';
$old_subscriptions = get_option(MailPress::option_name_subscriptions);

$subscriptions = $_POST['subscriptions'];

if (!isset($_POST['mailinglist']['on']))
{	// so we don't delete settings if addon deactivated !
	if (isset($old_subscriptions['display_mailinglists'])) $subscriptions['display_mailinglists'] 	= $old_subscriptions['display_mailinglists'];
}

if (isset($_POST['newsletter']['on']))
{
	if (!isset($subscriptions['default_newsletters'])) 	 $subscriptions['default_newsletters'] 	= array();
	$old_default_newsletters = (isset($old_subscriptions ['default_newsletters'])) ? $old_subscriptions ['default_newsletters'] : MP_Newsletters::get_defaults();

	$diff_default_newsletters = array();
	foreach($subscriptions ['default_newsletters'] as $k => $v) if (!isset($old_default_newsletters[$k])) $diff_default_newsletters[$k] = true;
	foreach($old_default_newsletters as $k => $v) if (!isset($subscriptions ['default_newsletters'][$k])) $diff_default_newsletters[$k] = true;
	foreach ($diff_default_newsletters as $k => $v) MP_Newsletters::reverse_subscriptions($k);
}
else  
{	// so we don't delete settings if addon deactivated !
	if (isset($old_subscriptions['newsletters'])) 		 $subscriptions['newsletters'] 		= $old_subscriptions['newsletters'];
	if (isset($old_subscriptions['default_newsletters']))  $subscriptions['default_newsletters'] 	= $old_subscriptions['default_newsletters'];

}
	
$mp_subscriptions = $subscriptions;
if (isset($_POST['newsletter']['on'])) MailPress_newsletter::plugins_loaded();
	
update_option(MailPress::option_name_subscriptions, $mp_subscriptions);
update_option(MailPress::option_name_general, $mp_general);

//do_action('mp_schedule_newsletters', array('event' => 'Update subscriptions'));

$message = __('"Subscriptions" settings saved', MP_TXTDOM);