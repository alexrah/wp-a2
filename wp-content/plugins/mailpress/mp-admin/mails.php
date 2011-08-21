<?php
class MP_AdminPage extends MP_Admin_page_list
{
	const screen 	= MailPress_page_mails;
	const capability 	= 'MailPress_edit_mails';
	const help_url	= 'http://www.mailpress.org/wiki/index.php?title=Help:Admin:Mails';
	const file        = __FILE__;

////  Redirect  ////

	public static function redirect() 
	{
		if     ( !empty($_REQUEST['action'])  && ($_REQUEST['action']  != -1))	$action = $_REQUEST['action'];
		elseif ( !empty($_REQUEST['action2']) && ($_REQUEST['action2'] != -1) )	$action = $_REQUEST['action2'];
		if (!isset($action)) return;

		$url_parms 	= self::get_url_parms();
		$checked	= (isset($_GET['checked'])) ? $_GET['checked'] : array();

		$count	= str_replace('bulk-', '', $action);
		$count     .= 'd';
		$$count	= 0;

		switch($action)
		{
			case 'bulk-archive' :
				foreach($checked as $id) if (MP_Mails::set_status($id, 'archived'))  $$count++;
			break;
			case 'bulk-unarchive' :
				foreach($checked as $id) if (MP_Mails::set_status($id, 'sent')) 	$$count++;
			break;
			case 'bulk-send' :
				$sent = $notsent = 0;
				foreach($checked as $id)
				{
					if ('draft' != MP_Mails::get_status($id)) continue;
					$x = MP_Mails::send_draft($id);
					$url = (is_numeric($x))	? $sent += $x : $notsent++ ;
				}
				if ($sent)    $url_parms['sent']    = $sent;
				if ($notsent) $url_parms['notsent'] = $notsent;
			break;
			case 'bulk-delete' :
				foreach($checked as $id) if (MP_Mails::set_status($id, 'delete')) $$count++;
			break;
		}

		if ($$count) $url_parms[$count] = $$count;
		self::mp_redirect( self::url(MailPress_mails, $url_parms) );
	}

////  Styles  ////

	public static function print_styles($styles = array()) 
	{
		wp_register_style ( self::screen, 		'/' . MP_PATH . 'mp-admin/css/mails.css',       array('thickbox') );

		$styles[] = self::screen;
		parent::print_styles($styles);
	}

//// Scripts ////

	public static function print_scripts($scripts = array()) 
	{
		$scripts = apply_filters('MailPress_autorefresh_js', $scripts);

		wp_register_script( 'mp-ajax-response', 	'/' . MP_PATH . 'mp-includes/js/mp_ajax_response.js', array('jquery'), false, 1);
		wp_localize_script( 'mp-ajax-response', 		'wpAjax', array( 
			'noPerm' => __('Email was not sent AND/OR Update database failed', MP_TXTDOM), 
			'broken' => __('An unidentified error has occurred.'), 
			'l10n_print_after' => 'try{convertEntities(wpAjax);}catch(e){};' 
		));

		wp_register_script( 'mp-lists', 		'/' . MP_PATH . 'mp-includes/js/mp_lists.js', array('mp-ajax-response'), false, 1);
		wp_localize_script( 'mp-lists', 			'wpListL10n', array( 
			'url' => MP_Action_url
		));

		wp_register_script( 'mp-thickbox', 		'/' . MP_PATH . 'mp-includes/js/mp_thickbox.js', array('thickbox'), false, 1);

		wp_register_script( self::screen, 		'/' . MP_PATH . 'mp-admin/js/mails.js', array('mp-thickbox', 'mp-lists'), false, 1);
		wp_localize_script( self::screen, 	'MP_AdminPageL10n', array(	
			'pending' => __('%i% pending'), 
			'screen' => self::screen, 
			'l10n_print_after' => 'try{convertEntities(MP_AdminPageL10n);}catch(e){};' 
		));

		$scripts[] = self::screen;
		parent::print_scripts($scripts);
	}

//// Columns ////

	public static function get_columns() 
	{
		$disabled = (!current_user_can('MailPress_delete_mails')) ? " disabled='disabled'" : '';
		$columns = array(	'cb' 		=> "<input type='checkbox'$disabled />", 
					'title' 	=> __('Subject', MP_TXTDOM), 
					'author' 	=> __('Author'), 
					'theme' 	=> __('Theme', MP_TXTDOM), 
					'to' 		=> __('To', MP_TXTDOM), 
					'date'	=> __('Date') );
		$columns = apply_filters('MailPress_mails_columns', $columns);
		return $columns;
	}

//// List ////

	public static function get_list($start, $num, $url_parms, $void = '') 
	{
		global $wpdb;

		$where = " AND status <> '' ";

		if (isset($url_parms['s']))
		{
			$sc = array('theme', 'themedir', 'template', 'toemail', 'toname', 'subject', 'html', 'plaintext', 'created', 'sent' );

			$where .= self::get_search_clause($url_parms['s'], $sc);
		}

		if (isset($url_parms['status']) && !empty($url_parms['status']))
			$where .= " AND status = '" . $url_parms['status'] . "'";
		if (isset($url_parms['author']) && !empty($url_parms['author']))
			$where .= " AND ( created_user_id = " . $url_parms['author'] . "  OR sent_user_id = " . $url_parms['author'] . " ) ";
		if (!current_user_can('MailPress_edit_others_mails'))
			$where .= " AND ( created_user_id = " . MailPress::get_wp_user_id() . " ) ";

		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM $wpdb->mp_mails WHERE 1=1 $where ORDER BY created DESC";

		list($_mails, $total) = parent::get_list($start, $num, $query, 'mp_mail');

		$subsubsub_urls = false;

		$libs = array( 'all' => __('All'), 'draft' =>	__('Draft', MP_TXTDOM), 'unsent' => __('Unsent', MP_TXTDOM), 'sending' => __('Pending', MP_TXTDOM), 'sent' => __('Sent', MP_TXTDOM), 'archived' => __('Archive', MP_TXTDOM), 'search' => __('Search Results') );

		$counts = array();
		$query = "SELECT status, count(*) as count FROM $wpdb->mp_mails GROUP BY status;";
		$statuses = $wpdb->get_results($query);

		if ($statuses)
		{
			$status_links_url  = MailPress_mails ;

			$status_links_url .= (isset($url_parms['mode'])) ? "&amp;mode=" . $url_parms['mode']  : '';

			foreach($statuses as $status) if ($status->count) $counts[$status->status] = $status->count;
			$counts['all'] = $wpdb->get_var("SELECT count(*) FROM $wpdb->mp_mails WHERE status <> '';");
			if (isset($url_parms['s'])) $counts['search'] = count($_mails);
			$out = array();

			foreach($libs as $k => $lib)
			{
				if (!isset($counts[$k]) || !$counts[$k]) continue;

	            	$url = $status_links_url . ( ('search' == $k) ? '&s=' . $url_parms['s'] : ( ('all' == $k) ? '' : "&amp;status=$k" ) );

				$sum = $counts[$k];

				$cls = '';
				if (isset($url_parms['s'])) 	  	 { if ('search' == $k) 			$cls = " class='current'"; }
				elseif (isset($url_parms['status'])) { if ($url_parms['status'] == $k ) $cls = " class='current'"; }
				elseif ('all' == $k)									$cls = " class='current'"; 

				$out[] = "<a$cls href='$url'>$lib <span class='count'>(<span class='mail-count-$k'>$sum</span>)</span></a>";
			}

			if (!empty($out)) $subsubsub_urls = '<li>' . join( ' | </li><li>', $out ) . '</li>';
		}
		return array($_mails, $total, $subsubsub_urls);
	}

////  Row  ////

	public static function get_row( $id, $url_parms, $xtra = false) 
	{
		global $mp_mail;

		$mp_mail = $mail = MP_Mails::get( $id );
		$the_mail_status = $mail->status;

// url's
		$args = array();
		$args['id'] 	= $id;

		$edit_url    	= esc_url(self::url( MailPress_edit, array_merge($args, $url_parms) ));

		$args['action'] 	= 'archive';
		$archive_url 	= esc_url(MailPress::url( MailPress_write, array_merge($args, $url_parms), "archive-mail_{$mail->id}" ));

		$args['action'] 	= 'unarchive';
		$unarchive_url 	= esc_url(MailPress::url( MailPress_write, array_merge($args, $url_parms), "unarchive-mail_{$mail->id}" ));

		$args['action'] 	= 'send';
		$send_url    	= esc_url(self::url( MailPress_write, array_merge($args, $url_parms) ));

		$args['action'] 	= 'delete';
		$delete_url 	= esc_url(self::url( MailPress_write, array_merge($args, $url_parms), "delete-mail_$id" ));

		$args['action'] 	= 'iview';
		if ('draft' == $mail->status) if (!empty($mail->theme)) $args['theme'] 	= $mail->theme;
		$args['KeepThis'] = 'true'; $args['TB_iframe']= 'true'; $args['width'] = '600'; $args['height']	= '400';
		$view_url		= esc_url(self::url(MP_Action_url, $args));


// actions
		$actions = array();
		$actions['edit']	= "<a href='$edit_url'   	title='" .  __('Edit') . "'>" . __('Edit') . '</a>';
		$actions['send'] 	= "<a href='$send_url' 		title='" . __('Send this mail', MP_TXTDOM ) . "'>" . __( 'Send', MP_TXTDOM ) . '</a>';

		$actions = apply_filters('MailPress_mails_actions', $actions, $mp_mail, $url_parms);

		$actions['approve']   = "<a href='$unarchive_url' 	class='dim:the-mail-list:mail-$id:unapproved:e7e7d3:e7e7d3:?mode=" . $url_parms['mode'] . "' title='" . __('Unarchive this mail', MP_TXTDOM ) . "'>" . __( 'Unarchive', MP_TXTDOM ) . '</a>';
		$actions['unapprove'] = "<a href='$archive_url' 	class='dim:the-mail-list:mail-$id:unapproved:e7e7d3:e7e7d3:?mode=" . $url_parms['mode'] . "' title='" . __('Archive this mail', MP_TXTDOM )   . "'>" . __( 'Archive', MP_TXTDOM ) 	. '</a>';

		$actions['delete']= "<a href='$delete_url' 	class='delete:the-mail-list:mail-$id submitdelete' title='" . __('Delete this mail', MP_TXTDOM ) . "'>" 	. __('Delete', MP_TXTDOM) . '</a>';
		$actions['view'] 	= "<a href='$view_url' 		class='thickbox'  title='" . __('View', MP_TXTDOM ) . "'>"								. __('View', MP_TXTDOM) . '</a>';

		switch($mail->status)
		{
			case 'draft' :
				unset($actions['approve']);
				unset($actions['unapprove']);
			break;
			case 'sent' :
				unset($actions['send']);
				unset($actions['edit']);
			break;
			case 'unsent' :
			case 'sending' :
				unset($actions['approve']);
				unset($actions['unapprove']);
			default : /* 'archived' */
				unset($actions['send']);
				unset($actions['edit']);
				unset($actions['delete']);
			break;
		}

		if (!current_user_can('MailPress_send_mails')) 		unset($actions['send']);
		if (!current_user_can('MailPress_delete_mails')) 	unset($actions['delete']);
		if (!current_user_can('MailPress_archive_mails'))    {unset($actions['archive']); unset($actions['unarchive']);}

// table row 
//	class
		$row_class = '';
		if ('archived' == $the_mail_status)  $row_class = 'unapproved';
		if ('draft' == $the_mail_status)  $row_class = 'draft';
		if ('unsent' == $the_mail_status) $row_class = 'unsent';
// 	checkbox
		$disabled = (!current_user_can('MailPress_delete_mails') && !current_user_can('MailPress_send_mails')) ? " disabled='disabled'" : '';
//	to
		$draft_dest = MP_Users::get_mailinglists();

		switch (true)
		{
			case ($xtra) :
				$email_display = "<blink style='color:red;font-weight:bold;'>" . $xtra . '</blink>';
			break;
			case (is_email($mail->toemail)) :
				$mail_url = self::url(MailPress_mails, $url_parms);
				$mail_url = remove_query_arg('s', $mail_url);
				$mail_url = esc_url( $mail_url . '&s=' . $mail->toemail );

				$email_display = '';
				$mail_url2 	    = "<a class='row-title' href='$mail_url'  title='" . sprintf( __('Search "%1$s"', MP_TXTDOM), $mail->toemail) . "'>";
				if ( ('detail' == $url_parms['mode']) && (get_option('show_avatars') ) )
				{
					$email_display .= "<div style='float:left;margin-right:10px;'>";
					$email_display .= $mail_url2;
					$email_display .= get_avatar( $mail->toemail, 32 );
					$email_display .= '</a>';
					$email_display .= '</div>';
				}
				$email_display .= "<div style='float:left;'>";
				$email_display .= $mail_url2;
				$email_display .= '<strong>';
				$email_display .= ( strlen($mail->toemail) > 40 ) ? substr($mail->toemail, 0, 39) . '...' : $mail->toemail;
				$email_display .= '</strong>';
				$email_display .= '</a>';
				if (!empty($mail->toname)) $email_display .= '<br />' . $mail->toname;
				$email_display .= '</div>';
			break;
			case (isset($draft_dest[$mail->toemail])) :
				$email_display = "<strong>" . $draft_dest[$mail->toemail] . "</strong>";
			break;
			case (is_serialized($mail->toemail)) :
				$email_display = "<div class='num post-com-count-wrapper'><a class='post-com-count'><span class='comment-count'>" . count(unserialize($mail->toemail)) . "</span></a></div>"; 
			break;
			default  :
				$email_display = "<span style='color:red;font-weight:bold;'>" . __('(unknown)', MP_TXTDOM) . '</span>';
				unset($actions['send']);
			break;
		}
		$email_display = apply_filters('MailPress_to_mails_column', $email_display, $mail);
//	author
		$author = ( 0 == $mail->sent_user_id) ? $mail->created_user_id : $mail->sent_user_id;
		if ($author != 0 && is_numeric($author)) 
		{
			unset($url_parms['author']);
			$wp_user 		= get_userdata($author);
			$author_url 	= esc_url(self::url( MailPress_mails . "&author=" . $author, $url_parms ));
		}
//	subject
		$metas = MP_Mailmeta::get( $id, '_MailPress_replacements' );
		$subject_display = $mail->subject;
		if ($metas) foreach($metas as $k => $v) $subject_display = str_replace($k, $v, $subject_display);
//	attachements
		$attach = false;
		$metas = MP_Mailmeta::has( $id, '_MailPress_attached_file' );
		if ($metas)
		{
			foreach($metas as $meta)
			{
				$meta_value = unserialize( $meta['meta_value'] );
				if ($the_mail_status == 'sent')
				{
					$attach = true;
					break;
				}
				elseif (is_file($meta_value['file_fullpath']))
				{
					$attach = true;
					break;
				}
			}
		}

?>
	<tr id="mail-<?php echo $id; ?>" class='<?php echo $row_class; ?>'>
<?php
		$columns = self::get_columns();
		$hidden  = self::get_hidden_columns();

		foreach ( $columns as $column_name => $column_display_name ) 
		{
			$class = "class='$column_name column-$column_name'";

			$style = '';
			if ('unsent' == $mail->status) 		$style .= 'font-style:italic;';
			if ( in_array($column_name, $hidden) ) 	$style .= 'display:none;';
			$style = ' style="' . $style . '"';

			$attributes = "$class$style";

			switch ($column_name) 
			{

				case 'cb':
					if (in_array($mail->status, array('unsent', 'archived')))
					{
?>
		<th class='check-column' scope='row'>
		</th>
<?php 				}
					else 
					{ 
?>
		<th class='check-column' scope='row'>
			<input type='checkbox' name='checked[]' value='<?php echo $id; ?>'<?php echo $disabled; ?> />
		</th>
<?php
	 				} 
				break;
				case 'title':
					$attributes = 'class="post-title column-title"' . $style;
?>
		<td  <?php echo $attributes ?>>
<?php
			if ($attach) :
?>
			<img class='attach' alt="<?php _e('Attachments', MP_TXTDOM); ?>" title="<?php _e('Attachments', MP_TXTDOM); ?>"  src='<?php echo get_option('siteurl') . '/' . MP_PATH; ?>mp-admin/images/clip.gif' />
<?php
			endif;
			if (('draft' == $mail->status) && ($mp_mail->sent >= $mp_mail->created)) :
?>
			<img class='attach' alt="<?php _e('Scheduled', MP_TXTDOM); ?>" title="<?php _e('Scheduled', MP_TXTDOM); ?>"  src='<?php echo get_option('siteurl') . '/' . MP_PATH; ?>mp-admin/images/scheduled.png' />
<?php
			endif;
			do_action('MailPress_get_icon_mails', $id);
?>
			<strong>
				<a class='row-title<?php echo ('draft' == $mail->status) ? '' : ' thickbox'; ?>' href='<?php echo ('draft' == $mail->status) ? $edit_url : $view_url; ?>' title='<?php printf( ('draft' == $mail->status) ?  __('Edit "%1$s"', MP_TXTDOM) : __('View "%1$s"', MP_TXTDOM) , ( '' == $subject_display) ? __('(no subject)', MP_TXTDOM) : htmlspecialchars($subject_display, ENT_QUOTES) ); ?>'>
					<?php echo ( '' == $subject_display) ? __('(no subject)', MP_TXTDOM) : (( strlen($subject_display) > 40 ) ? $subject_display = substr($subject_display, 0, 39) . '...' : $subject_display); ?>
				</a>
<?php if ('draft' == $mail->status) echo ' - ' . __('Draft'); ?>
<?php if ('archived' == $mail->status) echo ' - ' . __('Archive', MP_TXTDOM); ?>
			</strong>
<?php			echo self::get_actions($actions); ?>
		</td>
<?php
				break;
				case 'author':
?>
		<td  <?php echo $attributes ?>>
<?php					if ($author != 0 && is_numeric($author)) { ?>
			<a href='<?php echo $author_url; ?>' title='<?php printf( __('Mails by "%1$s"', MP_TXTDOM), $wp_user->display_name); ?>'><?php echo $wp_user->display_name; ?></a>
<?php 				} else _e("(unknown)", MP_TXTDOM); ?>
		</td>
<?php
				break;
				case 'theme':
?>
		<td  <?php echo $attributes ?>>
			<?php echo $mail->theme; ?>
			<?php if ('' != $mail->template) echo "<br />(" . $mail->template . ")"; ?>

		</td>
<?php
				break;
				case 'to':
?>
		<td  <?php echo $attributes ?>>
<?php echo $email_display; ?>
		</td>
<?php
				break;
				case 'date':
					$date_status = ('draft' == $mail->status) ? (($mp_mail->sent >= $mp_mail->created) ? true : __('Last Modified', MP_TXTDOM)) : __('Sent', MP_TXTDOM);
					$_scheduled = false;
					if (true === $date_status)
					{
						$_scheduled = true;
						$date_status= __('Scheduled', MP_TXTDOM);
					}

					$t_time = self::get_mail_date(__('Y/m/d H:i:s'));
					$m_time = self::get_mail_date_raw();

					$time   = strtotime( get_gmt_from_date( $m_time ) );
					$time_diff = current_time('timestamp', true) - $time;

					if ($_scheduled)	$h_time = mysql2date(__('Y/m/d'), $m_time);
					else 			$h_time = self::human_time_diff(self::get_mail_date_raw());
?>
		<td  <?php echo $attributes ?>>
			<abbr title="<?php echo $t_time; ?>"><?php echo $h_time; ?></abbr>
			<br />
			<?php echo $date_status; ?>
		</td>
<?php
				break;
				default:
?>
		<td  <?php echo $attributes ?>>
			<?php	do_action('MailPress_mails_get_row', $column_name, $mail, $url_parms); ?>
		</td>
<?php
				break;
			}
		}
?>
	  </tr>
<?php
	}

	public static function mail_date($d = '') {
		echo  self::get_mail_date($d);
	}

	public static function get_mail_date($d = '' ) {
		$x = self::get_mail_date_raw();
		return ( '' == $d ) ? mysql2date( get_option('date_format'), $x) : mysql2date($d, $x);
	}

	public static function get_mail_date_raw() {
		global $mp_mail;
		$x = ($mp_mail->sent >= $mp_mail->created) ? $mp_mail->sent : $mp_mail->created;
		return $x;
	}
}