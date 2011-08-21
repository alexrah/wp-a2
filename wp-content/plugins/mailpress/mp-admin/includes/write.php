<?php
global $draft;

$url_parms 	= MP_AdminPage::get_url_parms();

//
// MANAGING RESULTS
//

if (isset($_GET['id']))
{
	$draft 	= MP_Mails::get($_GET['id']);
	$rev_ids 	= MP_Mailmeta::get($draft->id, '_MailPress_mail_revisions');
}

$autosave	= true;
$notice 	= false;

$h2 		= __('Add New Mail', MP_TXTDOM);
$hidden  	= "\t\t\t<input type='hidden' id='mail_id' name='id' value='0' />\n";
$list_url 	= MP_AdminPage::url(MailPress_mails, $url_parms);

if (isset($draft))
{
	$h2 		= sprintf( __('Edit Draft # %1$s', MP_TXTDOM), $draft->id);
	$hidden  	= "\t\t\t<input type='hidden' id='mail_id' name='id' value='$draft->id' />\n";
	$delete_url = esc_url(MailPress_write ."&amp;action=delete&amp;id=$draft->id");

	$last_user 	= get_userdata($draft->created_user_id);
	$lastedited	= sprintf(__('Last edited by %1$s on %2$s at %3$s', MP_TXTDOM), esc_html( $last_user->display_name ), mysql2date(get_option('date_format'), $draft->created), mysql2date(get_option('time_format'), $draft->created));

/* revisions */
	if (is_array($rev_ids))
	{
		foreach ($rev_ids as $rev_user => $rev_id)
		{
			global $current_user ;
			if ($current_user->ID == $rev_user)
			{
				$revision = MP_Mails::get($rev_id);
				break;
			}
			else
			{
				$x = MP_Mails::get($rev_id);
				if ($x)
				{
					if ($x->created > $revision->created)
					{
						$revision = $x;
						$revision->not_this_user = true;
					}
				}
			}
		}
	}

	if (isset($revision))
	{
		if ($revision->created > $draft->created)
		{
			$autosave_data = MP_Mails::autosave_data();

			foreach ($autosave_data as $k => $v)
			{
				if ( wp_text_diff( $revision->$k, $draft->$k ) ) 
				{
					$autosave = false;

					$notice = sprintf( __( 'There is an autosave of this mail that is more recent than the version below.  <a href="%s">View the autosave</a>.', MP_TXTDOM ), esc_url(MailPress_revision . "&id=$draft->id&revision=$revision->id") );
					break;
				}
			}
		}
	}
	else
	{
		$revision = new stdClass();
		$revision->id = '0';
	}

	if ((isset($revision->not_this_user)) && ($revision->not_this_user)) $revision->id = '0';

	$hidden  	.= "\t\t\t<input type='hidden' id='mail_revision' 	name='revision' 	value='$revision->id' />\n";
/* end of revisions */

/* lock */

	if ($last = MP_Mails::check_mail_lock($draft->id))
	{
		$lock_user 	= get_userdata($last);
		$lock_user_name = $lock_user ? $lock_user->display_name : __('Somebody');
		$lock = sprintf( __( 'Warning: %s is currently editing this mail' ), esc_html( $lock_user_name ) );
	}
	else
	{
		MP_Mails::set_mail_lock($draft->id);
	}
/* end of lock */
}
else
{
	$draft = new stdClass();
	if (isset($_GET['toemail'])) $draft->toemail = $_GET['toemail'];
}

$draft->_scheduled = ( !isset($draft->sent) || '0000-00-00 00:00:00' == $draft->sent ) ? false : true;

if (isset($_SERVER['HTTP_REFERER']))
	$hidden .= "<input type='hidden' name='referredby' value='" . esc_url($_SERVER['HTTP_REFERER']) . "' />";

// what else ?
	do_action('MailPress_update_meta_boxes_write');

// messages
$class = 'fromto';
$message = ''; $err = 0;
if (isset($_GET['sched'])) 	{$err += 0; if (!empty($message)) $message .= '<br />'; $message .= sprintf( __('Mail scheduled for: <strong>%1$s</strong>. <a class="thickbox" href="%2$s">Preview mail</a>',  MP_TXTDOM), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $draft->sent ) ), esc_url(add_query_arg( array('action' => 'iview', 'id' => $draft->id, 'KeepThis' => 'true', 'TB_iframe' => 'true'), MP_Action_url )));}
if (isset($_GET['saved'])) 	{$err += 0; if (!empty($message)) $message .= '<br />'; $message .= __('Mail saved', MP_TXTDOM); }
if (isset($_GET['notsent'])) 	{$err += 1; if (!empty($message)) $message .= '<br />'; $message .= __('Mail NOT sent', MP_TXTDOM); }
if (isset($_GET['nomail'])) 	{$err += 1; if (!empty($message)) $message .= '<br />'; $message .= __('Please, enter a valid email',  MP_TXTDOM); $class = "TO"; }
if (isset($_GET['nodest'])) 	{$err += 1; if (!empty($message)) $message .= '<br />'; $message .= __('Mail NOT sent, no recipient',  MP_TXTDOM); $class = "TO"; }
if (isset($lock))			{$err += 1; if (!empty($message)) $message .= '<br />'; $message .= $lock; }
if ($notice)			{$err += 1; if (!empty($message)) $message .= '<br />'; $message .= $notice; } 	
if (isset($_GET['sent'])) 	{$err += 0; if (!empty($message)) $message .= '<br />'; $message .= sprintf( _n( __('%s mail sent', MP_TXTDOM), __('%s mails sent', MP_TXTDOM), $_GET['sent']), $_GET['sent']); }
if (isset($_GET['revision'])) {$err += 0; if (!empty($message)) $message .= '<br />'; $message .= sprintf( __('Mail restored to revision from %s', MP_TXTDOM), MP_Mails::mail_revision_title( (int) $_GET['revision'], false, $_GET['time']) ); }
$mp_general	= get_option(MailPress::option_name_general);

// from
$draft->fromemail = $mp_general['fromemail'];
$draft->fromname  = esc_attr($mp_general['fromname']); 

// to 
if (isset($draft->toemail))
{
	if (!is_email($draft->toemail))
	{
		$draft->to_list = $draft->toemail;
		$draft->toemail = $draft->toname = '';
	}
	else
	{
		$draft->toname  = (isset($draft->toname)) ? esc_attr($draft->toname) : '';
	}
}
else
	$draft->toemail = $draft->toname = '';

// or to
$draft_dest = MP_Users::get_mailinglists();

?>
	<div class='wrap'>
		<div id="icon-mailpress-mailnew" class="icon32"><br /></div>
		<h2><?php echo $h2; ?></h2>
<?php if ($message) MP_AdminPage::message($message, ($err) ? false : true); ?>
		<form id='writeform' name='writeform' action='' method='post'>

		<input type='hidden' 				name='action'  		value='draft' />
<?php echo $hidden; ?>
		<input type='hidden' id='user-id' 		name='user_ID' 		value="<?php echo MailPress::get_wp_user_id(); ?>" />

		<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); echo ("\n"); ?>
<?php if ( $autosave ) : ?>
		<?php wp_nonce_field( 'autosave', 'autosavenonce', false ); echo ("\n"); ?>
		<?php wp_nonce_field( 'getpreviewlink', 'getpreviewlinknonce', false ); echo ("\n"); ?>
		<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
<?php endif; ?>

		<div id='poststuff' class='metabox-holder has-right-sidebar'>
			<div id="side-info-column" class="inner-sidebar">
<?php do_meta_boxes(MP_AdminPage::screen, 'side', $draft); ?>
			</div>

			<div id='post-body'>
				<div id='post-body-content'>
					<div id='fromtodiv'>
						<table class='widefat'><tr><td style='border:none;'>
							<table class='form-table' style='margin:0;'>
								<tr>
									<td class='nombp'>
										<?php _e('To Email:', MP_TXTDOM); ?> 
									</td>	
									<td class='nombp' >
										<input  class='w90 <?php echo $class; ?>' type='text' name='toemail' id='toemail' value="<?php echo esc_attr($draft->toemail); ?>" title="<?php _e('Email', MP_TXTDOM); ?>" />
									</td>
									<td class='nombp' >
										<?php _e('OR all', MP_TXTDOM); ?>
										&nbsp;&nbsp;
										<select name='to_list' id='to_list'  class='<?php echo $class; ?>'>
<?php MP_AdminPage::select_optgroup($draft_dest, (isset($draft->to_list)) ? $draft->to_list : '') ?>
										</select>
									</td>
								</tr>
								<tr>
									<td class='nombp'>
										<?php _e('To Name:', MP_TXTDOM); ?> 
									</td>	
									<td class='nombp' >
										<input  class='w90 <?php echo $class; ?>' type='text' name='toname'  id='toname'  value="<?php echo esc_attr($draft->toname); ?>"  title="<?php _e('Name', MP_TXTDOM); ?>" />
									</td>
									<td class='nombp' >
									</td>
								</tr>
							</table>
						</td></tr></table>
					</div>
					<div id='titlediv'>
						<div id='titlewrap'>
							<label for='title' id='title-prompt-text' class='hide-if-no-js' style='<?php echo (isset($draft->subject)) ? 'visibility:hidden' : ''; ?>'><?php _e('Enter subject here', MP_TXTDOM); ?></label>
							<input type='text' name='subject' id='title' size='30' tabindex='1' autocomplete='off' value="<?php echo (isset($draft->subject)) ? esc_attr($draft->subject) : ''; ?>" />
						</div>
					</div>
					<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
<?php the_editor((isset($draft->html)) ? $draft->html : '', 'content', 'title', apply_filters('MailPress_upload_media', false), 5); ?>
						<div id="post-status-info">
							<span id="wp-word-count" class="alignleft"></span>
							<span class="alignright">
								<span id="autosave">&nbsp;</span>
								<span id="last-edit">
<?php if (isset($lastedited)) : ?>
									<?php echo $lastedited; ?>
<?php	endif; ?>
								</span>
							</span>
							<br class="clear" />
						</div>
					</div>
<?php do_meta_boxes(MP_AdminPage::screen, 'normal', $draft); ?>
				</div>
			</div>
		</div>
	</form>
</div>
<?php if (!MP_AdminPage::flash()) : ?>
	<div id='html-upload-iframes'></div>
<?php endif;