<?php
class MP_Autoresponder_event_mailinglist_new_unsubscriber extends MP_Autoresponders_event_mailinglist_abstract
{
	var $id    = 4;
	var $event = 'MailPress_mailinglist_new_unsubscriber';
}
new MP_Autoresponder_event_mailinglist_new_unsubscriber(__('New mailinglist UNsubscriber', MP_TXTDOM));