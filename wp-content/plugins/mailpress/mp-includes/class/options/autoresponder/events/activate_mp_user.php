<?php
class MP_Autoresponder_event_activate_mp_user extends MP_Autoresponders_event_abstract
{
	var $id    = 1;
	var $event = 'MailPress_activate_user';
}
new MP_Autoresponder_event_activate_mp_user(__('Subscription activated', MP_TXTDOM));