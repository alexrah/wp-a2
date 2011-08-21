<?php
class MP_Autoresponder_event_mailinglist_new_subscriber extends MP_Autoresponders_event_mailinglist_abstract
{
	var $id    = 3;
	var $event = 'MailPress_mailinglist_new_subscriber';
}
new MP_Autoresponder_event_mailinglist_new_subscriber(__('New mailinglist subscriber', MP_TXTDOM));