<?php
class MP_Mails
{
	const status_deleted = 'deleted';

	public static function get($mail, $output = OBJECT) 
	{
		switch (true)
		{
			case ( empty($mail) ) :
				if ( isset($GLOBALS['mp_mail']) ) 	$_mail = & $GLOBALS['mp_mail'];
				else						return null;
			break;
			case ( is_object($mail) ) :
				wp_cache_add($mail->id, $mail, 'mp_mail');
				$_mail = $mail;
			break;
			default :
				if ( isset($GLOBALS['mp_mail']) && ($GLOBALS['mp_mail']->id == $mail) ) 
				{
					$_mail = & $GLOBALS['mp_mail'];
				} 
				elseif ( ! $_mail = wp_cache_get($mail, 'mp_mail') ) 
				{
               		global $wpdb;
					$_mail = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->mp_mails WHERE id = %d LIMIT 1", $mail));
					if ($_mail) wp_cache_add($_mail->id, $_mail, 'mp_mail');
				}
			break;
		}

		if ( $output == OBJECT ) {
			return $_mail;
		} elseif ( $output == ARRAY_A ) {
			return get_object_vars($_mail);
		} elseif ( $output == ARRAY_N ) {
			return array_values(get_object_vars($_mail));
		} else {
			return $_mail;
		}
	}

	public static function get_var($var, $key_col, $key, $format = '%s') 
	{
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare("SELECT $var FROM $wpdb->mp_mails WHERE $key_col = $format LIMIT 1;", $key) );
	}

	public static function get_status($id) 
	{
		$result = self::get_var('status', 'id', $id);
		return ($result == NULL) ? self::status_deleted : $result;
	}

	public static function update_status($id, $status)
	{
		wp_cache_delete($id, 'mp_mail');

		$data = $format = $where = $where_format = array();

		$data['status'] 			= $status; 							$format[] = '%s';

		$where['id'] 			= (int) $id;						$where_format[] = '%d';

		global $wpdb;
		return $wpdb->update( $wpdb->mp_mails, $data, $where, $format, $where_format );
	}

	public static function set_status($id, $status) 
	{
		switch($status) 
		{
			case 'sent':
				if ('archived' == self::get_status($id)) return self::update_status($id, 'sent');
				return false;
			break;
			case 'archived':
				if ('sent' == self::get_status($id)) return self::update_status($id, 'archived');
				return false;
			break;
			case 'delete':
				return self::delete($id);
			break;
		}
		wp_cache_delete($id, 'mp_mail');
		return true;
	}

	public static function delete($id)
	{
		global $wpdb;
		do_action('MailPress_delete_mail', $id);

		$metas = MP_Mailmeta::has( $id, '_MailPress_mail_revisions' );
		if ($metas) {
			foreach($metas as $meta) {
				foreach(maybe_unserialize($meta['meta_value']) as $rev_id) {
					$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->mp_mails WHERE id = %d ; ", $rev_id ) );
		}}}
		wp_clear_scheduled_hook('mp_process_send_draft', $id);

		MP_Mailmeta::delete( $id );

		wp_cache_delete($id, 'mp_mail');

		return $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->mp_mails WHERE id = %d ; ", $id ) );
	}

	public static function process($id = NULL) 
	{
		if (NULL == $id) return false;
		$draft = self::get($id);
		if ('draft' != $draft->status) return;

		$time = strtotime( get_gmt_from_date( $draft->sent ) . ' GMT');

		if ( $time > current_time('timestamp', 'gmt') ) { // Uh oh, someone jumped the gun!
			wp_clear_scheduled_hook( 'mp_process_send_draft', $id ); // clear anything else in the system
			wp_schedule_single_event( $time, 'mp_process_send_draft', array( $id ) );
			return;
		}

		return self::send_draft($id);
	}

/// DRAFT ///

	public static function get_id($from = 'inconnu')
	{
		$wp_user = 

		$data = $format = array();

		$data['status'] 		= ''; 					$format[] = '%s';
		$data['created'] 		= current_time( 'mysql' );		$format[] = '%s';
		$data['created_user_id']= MailPress::get_wp_user_id();	$format[] = '%d';
		// longtext
		$data['toemail'] 		= ''; 					$format[] = '%s';
		$data['html'] 		= ''; 					$format[] = '%s';
		$data['plaintext'] 	= ''; 					$format[] = '%s';

		global $wpdb;
		$wpdb->insert($wpdb->mp_mails, $data, $format);

		return $wpdb->insert_id;
	}

	public static function update_draft($id, $status='draft')
	{
		global $wpdb;
		$id = (int) $id;

		wp_cache_delete($id, 'mp_mail');
		$draft = self::get($id);

// scheduled ?
		$scheduled = false;
		$draft->sent = '0000-00-00 00:00:00';

		wp_clear_scheduled_hook( 'mp_process_send_draft', $id );

		if (isset($_POST['aa'])) 
		{
			foreach ( array('aa', 'mm', 'jj', 'hh', 'mn') as $timeunit ) 
			{
				$$timeunit = $_POST[$timeunit];
				if ( $_POST['cur_' . $timeunit] == $_POST[$timeunit] ) continue;
	
				$scheduled = true;
			}
		// update schedule ?
			if ($scheduled)
			{
				$aa = ( $aa < 1 )  ? date('Y') : $aa;
				$maxd = array(31,(!($aa%4)&&($aa%100||!($aa%400)))?29:28,31,30,31,30,31,31,30,31,30,31); 
				$mm = ( $mm < 1 || $mm > 12 ) ? date('n') : $mm;
				$jj = ( $jj < 1 ) ? 1 : $jj;
				$jj = ( $jj > $maxd[$mm-1] ) ? $maxd[$mm-1] : $jj;
				$hh = ( $hh < 0 || $hh > 23 ) ? 00 : $hh;
				$mn = ( $mn < 0 || $mn > 59 ) ? 00 : $mn;
	
				$draft->sent = date('Y-m-d H:i:s', mktime($hh, $mn, 0, $mm, $jj, $aa));
				$sched_time  = strtotime( get_gmt_from_date( $draft->sent ) . ' GMT');

				wp_schedule_single_event( $sched_time, 'mp_process_send_draft', array( $id ) );

				$old_sched = strtotime( get_gmt_from_date( date('Y-m-d H:i:s', mktime($_POST['hidden_hh'], $_POST['hidden_mn'], 0, $_POST['hidden_mm'], $_POST['hidden_jj'], $_POST['hidden_aa']))) . ' GMT');
			}
		}

// process attachements
		if (isset($_POST['type_of_upload']))
		{
			$files = array();
			if (isset($_POST['Files'])) foreach ($_POST['Files'] as $k => $v) if (is_numeric($k)) $files[] = $k;

			$attach = (empty($files)) ? '' : join(', ', $files);

			$file_exits = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id FROM $wpdb->mp_mailmeta WHERE mp_mail_id = %d AND meta_key = %s", $id, '_MailPress_attached_file') . ( (empty($attach)) ? ';' : " AND meta_id NOT IN ($attach);" ) );
			if ($file_exits) foreach($file_exits as $entry) MP_Mailmeta::delete_by_id( $entry->meta_id );
		}

// recipients
		if (isset($_POST['to_list']) && !empty($_POST['to_list']))
		{
			$_POST['toemail'] = $_POST['to_list'];
			$_POST['toname']  = '';
		}

// content
		if (isset($_POST['content'])) $_POST['html'] = $_POST['content'];
		unset($_POST['content']);

		$_POST = stripslashes_deep($_POST);

		$data = $format = $where = $where_format = array();

		$data['status'] 		= $status; 								$format[] = '%s';
		$data['theme'] 		= (isset($_POST['Theme'])) ? $_POST['Theme'] : '';	$format[] = '%s';
		$data['toemail'] 		= trim($_POST['toemail']); 					$format[] = '%s';
		$data['toname'] 		= trim($_POST['toname']) ; 					$format[] = '%s';
		$data['subject'] 		= trim($_POST['subject']);					$format[] = '%s';
		$data['html'] 		= trim($_POST['html']); 					$format[] = '%s';
		$data['plaintext'] 	= trim($_POST['plaintext'], " \r\n"); 			$format[] = '%s';
		$data['created'] 		= isset($_POST['created']) ? $_POST['created'] : current_time( 'mysql' ); $format[] = '%s';
		$data['created_user_id']= MailPress::get_wp_user_id(); 				$format[] = '%d';
		$data['sent'] 		= $draft->sent; 							$format[] = '%s';

		if ($scheduled)
			$data['sent_user_id']   = $data['created_user_id'];				$format[] = '%d';

		$where['id'] 		= $id;								$where_format[] = '%d';
		$wpdb->update( $wpdb->mp_mails, $data, $where, $format, $where_format );

		return ( $scheduled && $sched_time != $old_sched );
	}

	public static function reset_scheduled($id = NULL)
	{
		if (NULL == $id) return false;
		$id = (int) $id;

		wp_clear_scheduled_hook('mp_process_send_draft', $id);

		$data = $format = $where = $where_format = array();

		$data['sent']	= '0000-00-00 00:00:00';	$format[] = '%s';

		$where['id'] 	= $id;				$where_format[] = '%d';

		global $wpdb;
		$wpdb->update( $wpdb->mp_mails, $data, $where, $format, $where_format );
	}

	public static function send_draft($id = NULL, $ajax = false, $_toemail = false, $_toname = false) 
	{
		if (NULL == $id) return false;
		$id = (int) $id;

		self::reset_scheduled($id);

		$template = apply_filters('MailPress_draft_template', false, $id);

		$draft = self::get($id);
		if ('draft' != $draft->status) return false;
		$mail 		= new stdClass();	/* so we duplicate the draft into a new mail */
		$mail->id 		= self::get_id(__CLASS__ . ' ' . __METHOD__);
		$mail->main_id 	= $id;

		if (!empty($draft->theme)) $mail->Theme = $draft->theme;
		if (!empty($template))     $mail->Template = $template;

		if ($_toemail)
		{
			$mail->toemail	= $_toemail;
			$mail->toname	= ($_toname) ? $_toname : '';
		}
		else
		{
			$query = self::get_query_mailinglist($draft->toemail);
			if ($query)
			{
				$mail->recipients_query = $query;
			}
			else
			{
				if 	(!is_email($draft->toemail)) return 'y';
				$mail->toemail	= $draft->toemail;
				$mail->toname	= $draft->toname;
			}
		}

		$mail->subject	= $draft->subject;
		$mail->html		= $draft->html;
		$mail->plaintext	= $draft->plaintext;

		$mail->wp_user_id	= $draft->created_user_id;

		$mail->draft 	= true;

		$count = MailPress::mail($mail);

		if (0 === $count)		return 'x'; // no recipient
		if (!$count) return 0;			// something wrong !

		if ($ajax) 	return array($mail->id);
		return $count;
	}

//// Recipients queries ////

	public static function get_query_mailinglist($draft_toemail)
	{
		switch ($draft_toemail)
		{
			case '1' :
           		global $wpdb;
				return "SELECT id, email, name, status, confkey FROM $wpdb->mp_users WHERE status = 'active';";
			break;
/* 2 & 3 used by comments */
			case '4' :
           		global $wpdb;
				return "SELECT id, email, name, status, confkey FROM $wpdb->mp_users WHERE status IN ('active', 'waiting');";
			break;
			default :
				return apply_filters('MailPress_query_mailinglist', false, $draft_toemail);
			break;
		}
		return false;
	}

/// DISPLAYING E-MAILS & NAMES ///

	public static function display_toemail($toemail, $toname, $tolist='', $selected=false)
	{
		$return = '';
		$draft_dest = MP_Users::get_mailinglists();

		if 		(!empty($tolist)  && isset($draft_dest[$tolist]))	return "<b>" . $draft_dest[$tolist] . "</b>"; 
		elseif 	(!empty($toemail) && isset($draft_dest[$toemail]))	return "<b>" . $draft_dest[$toemail] . "</b>"; 
		elseif 	(is_email($toemail))
		{
				return self::display_name_email($toname, $toemail);
		}
		else
		{
			$y = unserialize($toemail);
			unset($y['MP_Mail']);
			if (is_array($y))
			{
				$return = $s = '';
				foreach ($y as $k => $v)
				{
					if ((int) $selected == (int) $v['{{_user_id}}']) $s = ' selected="selected"';
					$return .= "<option$s>$k</option>";
					if (!empty($s)) $selected = -1;
					$s = '';
				}
				$selected = ($selected == -1) ? ' disabled="disabled"' : '';
				$return = "<select{$selected}>{$return}</select>";
				return $return;
			}
		}
		return false;
	}

	public static function display_name_email($name, $email)
	{
		if (empty($name)) return $email;
		return self::display_name(esc_attr($name), false) . " &lt;$email&gt;";
	}

	public static function display_name($name, $for_mail = true)
	{
		$default = '_';
		if ( is_email($name) )	$name = trim(str_replace('.', ' ', substr($name, 0, strpos($name, '@'))));
		if ( $for_mail ) 
		{ if ( empty($name) ) 	$name = $default; }
		else
		{ if ($default == $name)$name = '';}
		return $name;									
	}

//// Write ////

	public static function autosave_data()
	{
		$autosave_data['toemail'] 	= __('To', MP_TXTDOM); 
		$autosave_data['toname'] 	= __('Name', MP_TXTDOM); 
		$autosave_data['theme']		= __('Theme', MP_TXTDOM);
		$autosave_data['subject'] 	= __('Subject', MP_TXTDOM); 
		$autosave_data['html'] 		= __('Html');
		$autosave_data['plaintext']	= __('Plain Text', MP_TXTDOM);
		return $autosave_data;
	}

	public static function check_mail_lock( $id ) 
	{
		global $current_user;

		if ( !$mail = self::get( $id ) ) return false;

		$lock = MP_Mailmeta::get( $id, '_edit_lock' );
		$last = MP_Mailmeta::get( $id, '_edit_last' );
		$time_window = AUTOSAVE_INTERVAL * 2 ;

		if ( $lock && $lock > time() - $time_window && $last != $current_user->ID )	return $last;
		return false;
	}

	public static function set_mail_lock( $id ) 
	{
		global $current_user;
		if ( !$mail = self::get( $id ) )			return false;
		if ( !$current_user || !$current_user->ID )	return false;

		$now = time();

		if (!MP_Mailmeta::add(     $mail->id, '_edit_lock', $now, true))
			MP_Mailmeta::update( $mail->id, '_edit_lock', $now );
		if (!MP_Mailmeta::add(     $mail->id, '_edit_last', $current_user->ID, true))
			MP_Mailmeta::update( $mail->id, '_edit_last', $current_user->ID );
	}

////  Revisions ////

	public static function mail_revision_title( $revision, $link = true, $time = false) 
	{
		if ( !$revision = self::get( $revision ) ) return $revision;

		$datef = _x( 'j F, Y @ G:i', 'revision date format', MP_TXTDOM);
		$autosavef = __( '%s [Autosave]' , MP_TXTDOM);
		$currentf  = __( '%s [Current Revision]' , MP_TXTDOM);

		$gmt_offset = (int) get_option('gmt_offset');
		$sign = '+';
		if ($gmt_offset < 0) 				{$sign = '-'; $gmt_offset = $gmt_offset * -1;}
		if ($gmt_offset < 10) 				$gmt_offset = '0' . $gmt_offset;
		$gmt_offset = 					str_replace('.', '', $gmt_offset);
		while (strlen($gmt_offset) < 4) 		$gmt_offset = $gmt_offset . '0';
		$gmt_offset = $sign . $gmt_offset ;

		$time = ($time) ? $time : $revision->created;

		$date = date_i18n( $datef, strtotime( $time . ' ' . $gmt_offset ) );
		if ($link) $date = "<a href='" . esc_url($link) . "'>$date</a>";
	
		if ('' == $revision->status) 	$date = sprintf( $autosavef, $date );
		else					$date = sprintf( $currentf, $date );

		return $date;
	}

	public static function list_mail_revisions( $mail_id = 0, $args = null ) 
	{
		if ( !$mail = self::get( $mail_id ) ) return;

		$defaults = array( 'parent' => false, 'right' => false, 'left' => false, 'format' => 'list', 'type' => 'all' );
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		switch ( $type ) 
		{
			case 'autosave' :
				if ( !$rev_ids = MP_Mailmeta::get($mail->id, '_MailPress_mail_revisions')) return;
				break;
			case 'revision' : // just revisions - remove autosave later
			case 'all' :
			default :
				if ( !$rev_ids = MP_Mailmeta::get($mail->id, '_MailPress_mail_revisions')) return;
				break;
		}

		$titlef = _x( '%1$s by %2$s', 'mail revision 1:datetime, 2:name', MP_TXTDOM);

		$rev_ids[0] = $mail->id;
		ksort($rev_ids);

		$rows = '';
		$class = false;

		foreach ( $rev_ids as $k => $rev_id ) 
		{
			if (!$revision = self::get( $rev_id ) ) continue;

			$link = ('' == $revision->status) ? MailPress_revision . '&id=' . $mail->id . '&revision=' . $rev_id : MailPress_write . '&id=' . $mail->id;
			$date = self::mail_revision_title( $rev_id, $link );
			$name = ( $k != 0) ? get_author_name($k) : get_author_name($mail->created_user_id);

			if ( 'form-table' == $format ) {
				if ( $left )
					$left_checked = $left == $rev_id ? ' checked="checked"' : '';
				else
					$left_checked = $right_checked ? ' checked="checked"' : ''; // [sic] (the next one)
				$right_checked = $right == $rev_id ? ' checked="checked"' : '';

				$class = $class ? '' : " class='alternate'";

				if ( $k != 0)
					$actions = '<a href="' . wp_nonce_url( add_query_arg( array( 'page' => MailPress_page_revision, 'action' => 'restore', 'id' => $mail->id, 'revision' => $rev_id ) ), "restore-post_$mail->id|$rev_id" ) . '">' . __( 'Restore', MP_TXTDOM ) . '</a>';
				else
					$actions = '';

				$rows .= "<tr$class>\n";
				$rows .= "\t<th style='white-space: nowrap' scope='row'><input type='radio' name='left' value='$rev_id'$left_checked /><input type='radio' name='right' value='$rev_id'$right_checked /></th>\n";
				$rows .= "\t<td>$date</td>\n";
				$rows .= "\t<td>$name</td>\n";
				$rows .= "\t<td class='action-links'>$actions</td>\n";
				$rows .= "</tr>\n";
			} else {
				if ($k != 0)
				{
					$title = sprintf( $titlef, $date, $name );
					$rows .= "\t<li>$title</li>\n";
				}
			}
		}
	
		if ( 'form-table' == $format ) : 

?>
<form action='admin.php' method="get">
	<div class="tablenav">
		<div class="alignleft actions">
			<input type="submit" class="button-secondary" value="<?php _e( 'Compare Revisions', MP_TXTDOM ); ?>" />
			<input type="hidden" name="page"   value="<?php echo MailPress_page_mails; ?>" />
			<input type="hidden" name="file"   value="revision" />
			<input type="hidden" name="action" value="diff" />
			<input type="hidden" name="id"     value="<?php echo $mail->id; ?>" />
		</div>
	</div>
	<br class="clear" />
	<table class="widefat post-revisions">
		<col />
		<col style="width: 33%" />
		<col style="width: 33%" />
		<col style="width: 33%" />
		<thead>
			<tr>
				<th scope="col"></th>
				<th scope="col"><?php _e( 'Date Created', MP_TXTDOM ); ?></th>
				<th scope="col"><?php _e( 'Author' , MP_TXTDOM); ?></th>
				<th scope="col" class="action-links"><?php _e( 'Actions', MP_TXTDOM ); ?></th>
			</tr>
		</thead>
		<tbody>
<?php echo $rows; ?>
		</tbody>
	</table>
</form>
<?php
		else :
			echo "<ul class='post-revisions'>\n";
			echo $rows;
			echo "</ul>";
		endif;
	}

//// attachements

	public static function get_attachement_link($meta, $mail_status)
	{
		$meta_value = unserialize( $meta['meta_value'] );
		$href = esc_url(add_query_arg( array('action' => 'attach_download', 'id' => $meta['meta_id']), MP_Action_url ));

		if (in_array($mail_status, array('sent', 'archived')))
		{
			if (is_file($meta_value['file_fullpath']))
			{
				return "<a href='" . $href . "' style='text-decoration:none;'>" . $meta_value['name'] . "</a>";
			}
			else
			{
				return "<span>" . $meta_value['name'] . "</span>";
			}
		}
		else
		{
			if (is_file($meta_value['file_fullpath']))
			{
				return "<a href='" . $href . "' style='text-decoration:none;'>" . $meta_value['name'] . "</a>";
			}
		}
	}
}