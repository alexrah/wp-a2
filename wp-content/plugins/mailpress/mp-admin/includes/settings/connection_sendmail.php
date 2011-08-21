<?php
$mp_general['tab'] = 'connection_sendmail';

$connection_sendmail = $_POST['connection_sendmail'];

update_option(MailPress_connection_sendmail::option_name, $connection_sendmail);
update_option(MailPress::option_name_general, $mp_general);

$message = __("'SENDMAIL' settings saved", MP_TXTDOM);