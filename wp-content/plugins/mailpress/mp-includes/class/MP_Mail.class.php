<?php
class MP_Mail extends MP_Mail_api
{
	const option_name_smtp 	= 'MailPress_smtp_config';

	const name_required = true;
	const get8BitEncoding = true;  // setting this to false can have an impact on perf see http://forums.devnetwork.net/viewtopic.php?f=52&t=96933

	function __construct( $plug = MP_FOLDER )
	{
		$this->plug = $plug;

		$this->theme = new MP_Themes();

		$this->message 	= null;

		$this->args = new stdClass();
	}

////	MP_Mail send functions	////

	function send($args)
	{
		if (is_numeric($args))
		{
			$this->args = MP_Mails::get($args);
		}
		else
		{
			$this->args = $args ;
		}
		return $this->end( $this->start() );
	}

	function start()
	{
		MailPress::no_abort_limit();

		$this->row  = new stdClass();
		$this->mail = new stdClass();

		global $mp_general;
		$mp_general = get_option(MailPress::option_name_general);

////  Log it  ////

		$f = (isset($this->args->forcelog)) ? true : false; 
		$this->trace = new MP_Log($this->plug, MP_ABSPATH, __CLASS__, $f, 'general');

		if (!$this->args)	
		{
			$this->trace->log('MAILPRESS [ERROR] - Sorry invalid arguments in MP_Mail::send');
			return false;
		}

////  Build it  ////

		if (!isset($this->args->id))
		{
			$this->row->id = $this->args->id = MP_Mails::get_id( 'MP_Mail::start' );
		}
		else
			$this->row->id = $this->args->id;

	//¤ charset ¤//
		$this->row->charset = (isset($this->args->charset)) ? $this->args->charset : get_option('blog_charset');

	//¤ fromemail & fromname ¤//
		$this->row->fromemail 	= (empty($this->args->fromemail)) ? $mp_general['fromemail'] : $this->args->fromemail;
		$this->row->fromname 	= (empty($this->args->fromname))  ? $mp_general['fromname']  : $this->args->fromname;
		$this->row->fromname 	= (empty($this->row->fromname))   ? self::display_name($mp_general['fromemail']) : $this->row->fromname;

	//¤ recipients & replacements ¤//
		self::get_mail_replacements();

		$this->mail->recipients_count = $this->manage_recipients();
		if (!$this->mail->recipients_count)
		{
			$this->trace->log((0 === $this->mail->recipients_count) ? 'MAILPRESS [ERROR] - No recipient' : 'MAILPRESS [ERROR] - problems with recipients & replacements');
			return $this->mail->recipients_count;
		}

	//¤ subject ¤//
		$this->row->subject 	= (isset($this->args->subject)) ? $this->args->subject : false;
		$pt = $this->theme->get_page_templates_from((isset($this->args->Theme)) ? $this->args->Theme : $this->theme->themes[$this->theme->current_theme]['Template']);
		if (isset($this->args->Template)) if (isset($pt[$this->args->Template][1]) ) $this->row->subject = $pt[$this->args->Template][1];
		$this->row->subject 	= ($this->row->subject) ? trim($this->html_entity_decode($this->do_eval($this->row->subject))) : '';

	//¤ html ¤//
		$this->row->html		= $this->build_mail_content('html', 'the_content');

	//¤ plaintext ¤//
		$this->row->plaintext 	= $this->build_mail_content('plaintext');

	//¤ attachements ¤//
		$this->mail->attachements = false;
		if (isset($this->args->main_id))
		{
			$metas = MP_Mailmeta::has( $this->args->main_id, '_MailPress_attached_file');
			if ($metas)
			{
				foreach($metas as $meta)
				{
					$meta_value = unserialize( $meta['meta_value'] );
					if (!is_file($meta_value['file_fullpath'])) continue;
					$this->mail->attachements = true;
					MP_Mailmeta::add( $this->row->id, '_MailPress_attached_file', $meta_value );
				}
			}
		}

		unset($this->theme);

	//¤ mail empty ? ¤//
		if (!$this->row->subject && !$this->row->plaintext && !$this->row->html && !$this->mail->attachements)
		{
			$this->trace->log(__('MAILPRESS [WARNING] - Mail is empty', MP_TXTDOM));
			return false;
		}

		$this->row->theme		= $this->mail->theme;
		$this->row->themedir	= $this->mail->themedir;
		$this->row->template 	= $this->mail->template;

	//¤ no tracking on unknown recipient ! ¤//
		if (!isset($this->mail->external_recipient))
		{
			$this->row = apply_filters('MailPress_mail', $this->row);
			$this->replace_mail_urls();
		}

	//¤ only one recipient ¤//
		if (1 == $this->mail->recipients_count)
		{
			$toname = '';
			if (isset($this->row->recipients[0])) $toemail = $this->row->recipients[0]; else foreach($this->row->recipients as $toemail => $toname) {};
			$this->row->toemail = $toemail;
			$this->row->toname  = $toname;

			$this->row->replacements[$toemail] = array_merge($this->mail->replacements, $this->row->replacements[$toemail]);

			foreach($this->row->replacements[$toemail] as $k => $v) 
			{
				$this->row->subject 	= str_replace($k, $v, $this->row->subject, $cs);
				$this->row->plaintext 	= str_replace($k, $v, $this->row->plaintext, $cp);
				$this->row->html 		= str_replace($k, $v, $this->row->html, $ch);
			}

			if (isset($this->row->replacements[$toemail]['{{_user_id}}'])) $this->row->mp_user_id = $this->row->replacements[$toemail]['{{_user_id}}'];
			MP_Mailmeta::delete_by_id($this->mail->mmid);
			unset($this->row->replacements, $this->row->recipients, $this->mail->replacements, $this->mail->mmid);
		}

/*trace*/	$x  = " \n\n ------------------- start of mail -------------------  ";
/*trace*/	$x .= " \n From : " . $this->row->fromname  . " <" . $this->row->fromemail . "> ";
/*trace*/	if (isset($this->row->toemail))
/*trace*/		if (!empty($this->row->toname))
/*trace*/	$x .= " \n To   : " . $this->row->toname . " <" . $this->row->toemail . "> ";
/*trace*/		else
/*trace*/	$x .= " \n To   : " . $this->row->toemail;
/*trace*/	$x .= " \n Subject : " . $this->row->subject;
/*trace*/	if ($this->row->plaintext) 	$x .= " \n   ------------------- plaintext -------------------  \n " . $this->row->plaintext;
/*trace*/	if ($this->row->html) 		$x .= " \n\n ------------------- text/html -------------------  \n " . $this->row->html;
/*trace*/	$x .= " \n\n ------------------ end of mail ------------------  \n\n";
/*trace*/	$this->trace->log($x, $this->trace->levels[512]);

		$this->mail->swift_batchSend 		= (1 < $this->mail->recipients_count);
		$this->mail->mailpress_batch_send	= ($this->mail->swift_batchSend) ? apply_filters('MailPress_status_mail', false) : false;

////  Send it  ////

	//¤ no mail ? ¤//
		if (isset($this->args->nomail))
		{
			$this->trace->log('MAILPRESS [NOTICE] - :::: Mail not sent as required ::::');
		}
		elseif ($this->mail->mailpress_batch_send)
		{
			$this->trace->log('MAILPRESS [NOTICE] - :::: Mail batch processing as required ::::');
			do_action('MailPress_schedule_batch_send');
		}
		else
		{
			if (!$this->swift_processing())
			{
				$this->trace->log('');
				$this->trace->log('MAILPRESS [ERROR] - *** Mail could not be sent ***');
				$this->trace->log('');
				unset($this->message, $this->swift); 
				return false;
			}
		}
		unset($this->message, $this->swift); 

////  Archive it  ////

		if (isset($this->args->noarchive))
		{
			$this->trace->log('MAILPRESS [NOTICE] - :::: Mail not saved as required ::::');
			MP_Mails::delete($this->row->id);
		}
		else
		{ 
			global $wpdb;

			if (!isset($this->args->nostats)) MailPress::update_stats('t', isset($this->args->Template) ? $this->args->Template : '', $this->mail->recipients_count);

			$now		= current_time( 'mysql' );
			$user_id 	= (empty($this->args->wp_user_id)) ?  MailPress::get_wp_user_id() : $this->args->wp_user_id;

			if ($this->mail->swift_batchSend) 
			{
				foreach ($this->row->replacements as $email => $r)
				{
					if (isset($this->row->recipients[$email]))
						$this->row->toemail[$email] = $this->row->replacements[$email];
					unset($this->row->recipients[$email], $this->row->replacements[$email]);
				}
				foreach ($this->row->recipients as $k => $email)
				{
					$this->row->toemail[$email] = $this->row->replacements[$email];
					unset($this->row->recipients[$k], $this->row->replacements[$email]);
				}
				unset($this->row->recipients, $this->row->replacements);
				$this->row->toemail = serialize($this->row->toemail);
			}

	//¤ status ¤//
			$this->row->status = ($this->mail->mailpress_batch_send) ? (apply_filters('MailPress_status_mail', 'sent')) : 'sent';
			if (!isset($this->row->toname)) $this->row->toname = '';

			wp_cache_delete($this->row->id, 'mp_mail');

			$data = $format = $where = $where_format = array();

			$data['status'] 		= $this->row->status; 	$format[] = '%s';
			$data['theme'] 		= $this->row->theme; 	$format[] = '%s';
			$data['themedir'] 	= $this->row->themedir; $format[] = '%s';
			$data['template'] 	= $this->row->template; $format[] = '%s';
			$data['fromemail'] 	= $this->row->fromemail;$format[] = '%s';
			$data['fromname'] 	= $this->row->fromname;	$format[] = '%s';
			$data['toemail'] 		= $this->row->toemail; 	$format[] = '%s';
			$data['toname'] 		= $this->row->toname; 	$format[] = '%s';
			$data['charset'] 		= $this->row->charset; 	$format[] = '%s';
			$data['subject'] 		= $this->row->subject;	$format[] = '%s';
			$data['html'] 		= $this->row->html; 	$format[] = '%s';
			$data['plaintext'] 	= $this->row->plaintext;$format[] = '%s';
			$data['created'] 		= $now; 			$format[] = '%s';
			$data['created_user_id']= $user_id; 		$format[] = '%d';
			$data['sent'] 		= $now; 			$format[] = '%s';
			$data['sent_user_id'] 	= $user_id; 		$format[] = '%d';

			$where['id'] 		= (int) $this->row->id;		$where_format[] = '%d';

			if ( !$wpdb->update( $wpdb->mp_mails, $data, $where, $format, $where_format ) )
			{
				$this->trace->log(sprintf('MAILPRESS [ERROR] - *** Database error, Mail not saved : %1$s '."\n".'%2$s', $wpdb->last_error, $wpdb->last_query));
				return false;
			}
			$this->trace->log('MAILPRESS [NOTICE] - :::: MAIL SAVED ::::');
		}

		return $this->mail->recipients_count;
	}

	function end($rc)
	{
		$this->trace->end($rc);
		return $rc;
	}


////  Build it  ////


 //¤ mail replacements ¤//

	function get_mail_replacements()
	{
		$mail_replacements = $this->convert_all($this->args);
		$unsets = array( '{{subject}}', '{{content}}', '{{html}}', '{{plaintext}}', '{{id}}', '{{main_id}}', '{{recipients_query}}', '{{draft}}', '{{advanced}}' );
		foreach ($unsets as $unset) unset($mail_replacements[$unset]);

		$mail_main_id = (!isset($this->args->main_id)) ? 0 : $this->args->main_id;
		$_mail_id = ($mail_main_id) ? $mail_main_id : $this->row->id;
		$m = MP_Mailmeta::get_replacements($_mail_id);
		if (!is_array($m)) $m = array();

		$this->mail->replacements = array_merge($m, $mail_replacements);

		$this->mail->mmid = MP_Mailmeta::add( $this->row->id, '_MailPress_replacements', $this->mail->replacements );
	}

	function convert_all($x='', $sepb='{{', $sepa='}}', $before='', $first=0, $array=array())
	{
		if (is_object($x)) $x = get_object_vars($x);
		if (empty($x)) return array();
		foreach($x as $key => $value)
		{
			if (!(is_object($value) || is_array($value)))
			{
				$x = (0 == $first) ? $key : $before . '[' . $key . ']'; 
				$array[$sepb . $x . $sepa] = $this->html_entity_decode($value);;
			}
			else 
			{
				$abefore= (!$first) ? $key : $before . '[' . $key . ']'; 
				$array = array_merge($array, $this->convert_all($value , $sepb , $sepa , $abefore , $first + 1 ) );
			}
		}
		return $array;
	}

 //¤ recipients & replacements ¤//

	function manage_recipients()
	{
		if (isset($this->args->replacements))
		{
			if (isset($this->args->recipients))
			{
				$this->row->replacements = $this->args->replacements;
				$this->row->recipients   = $this->args->recipients;
				return count($this->row->recipients);
			}
			else
			{
				$this->get_old_recipients();
				return count($this->row->recipients);
			}
		}

		if (!isset($this->args->recipients_query))
		{
			if (is_email($this->args->toemail))
			{
				$mp_user_id = MP_Users::get_id_by_email($this->args->toemail);
				if ($mp_user_id)
				{
					global $wpdb;
					$this->args->recipients_query = "SELECT DISTINCT id, email, name, status, confkey FROM $wpdb->mp_users WHERE id = $mp_user_id ;";
				}
			}
		}

		if (isset($this->args->recipients_query))
		{
			$this->get_recipients($this->args->recipients_query);

			$this->args->viewhtml = MP_Users::get_view_url('{{_confkey}}', $this->row->id);
			if (isset($this->args->subscribe)) 	$this->args->subscribe   = MP_Users::get_subscribe_url('{{_confkey}}');
			else						$this->args->unsubscribe = MP_Users::get_unsubscribe_url('{{_confkey}}');

			return count($this->row->recipients);
		}

		if (is_email($this->args->toemail))
		{
			$this->mail->external_recipient = true;
			$this->get_external_recipient();
			return 1;
		}

		return false;
	}

	function get_recipients($query)
	{
		global $wpdb;
		$mp_users = $wpdb->get_results( $query );

		$this->row->recipients = array();
		if ($mp_users)
		{
			foreach ($mp_users as $mp_user)
			{
				$this->row->replacements[$mp_user->email] = $this->get_user_replacements($mp_user);

				if (isset($this->args->toname)) if (count($mp_users) == 1) $mp_user->name = $this->args->toname;
				if ( empty($mp_user->name) )
					if (self::name_required)
						$this->row->recipients[$mp_user->email] 	= trim(str_replace('.', ' ', substr($mp_user->email, 0, strpos($mp_user->email, '@'))));
					else
						$this->row->recipients[] 			= $mp_user->email;
				else
					$this->row->recipients[$mp_user->email] 		= $mp_user->name;
			}
		}
	}

	function get_external_recipient()
	{
		$this->trace->log('MAILPRESS [NOTICE] - :::: external recipient ::::');
		$this->row->replacements[$this->args->toemail] = array();

		if (isset($this->args->toname))
			if (!empty($this->args->toname))
				$this->row->recipients[$this->args->toemail] = $this->args->toname;
			else
				if (self::name_required)
					$this->row->recipients[$this->args->toemail] 	= trim(str_replace('.', ' ', substr($this->args->toemail, 0, strpos($this->args->toemail, '@'))));
				else
					$this->row->recipients[] = $this->args->toemail;
		else
			$this->row->recipients[] = $this->args->toemail;
	}

	function get_old_recipients()
	{
		$this->row->replacements = $this->args->replacements;

		foreach($this->row->replacements as $email => $v)
		{
			if (isset($v['{{toname}}']) && !empty($v['{{toname}}']))
				$this->row->recipients[$email] = $v['{{toname}}'];
			else
			{
				if (!self::name_required)
					$this->row->recipients[] = $email;
				else
				{
					$name = trim(str_replace('.', ' ', substr($email, 0, strpos($email, '@'))));
					$this->row->recipients[$email] = $this->row->replacements[$email]['{{toname}}'] =  $name;
				}
			}
		}
	}

	function get_user_replacements($mp_user)
	{
		if (!$mp_user) return array();

		if (is_numeric($mp_user))
		{
			$mp_user = MP_Users::get($mp_user);
			if (!$mp_user) return array();
		}

		$replacements = MP_Usermeta::get_replacements($mp_user->id);

		$replacements ['{{toemail}}']	= $mp_user->email;
		$replacements ['{{toname}}']	= $mp_user->name;
		$replacements ['{{_user_id}}']= $mp_user->id;

	//¤ always last ¤//
		$replacements ['{{_confkey}}']= $mp_user->confkey;
		return $replacements;
	}

	function replace_mail_urls()
	{
		$r = array();
		if (isset($this->args->viewhtml)) 	$r['{{viewhtml}}'] 	= $this->args->viewhtml;
		if (isset($this->args->subscribe))	$r['{{subscribe}}'] 	= $this->args->subscribe;
		if (isset($this->args->unsubscribe))$r['{{unsubscribe}}'] 	= $this->args->unsubscribe;

		foreach($r as $k => $v)
		{
			$this->row->subject 	= str_replace($k, $v, $this->row->subject, $cs);
			$this->row->plaintext 	= str_replace($k, $v, $this->row->plaintext, $cp);
			$this->row->html 		= str_replace($k, $v, $this->row->html, $ch);
		}
	}

 //¤ plaintext, html ¤//

	function build_mail_content($type, $filter=false)
	{
		$content 		= '<?php $this->get_header(); $this->the_content(); $this->get_footer(); ?>';
		$template_file 	= 'default.php';

		$this->build = new stdClass();
		$this->build->plaintext = ('plaintext' == $type);
		$this->build->filter = $filter;

		if (!isset($this->mail)) $this->mail = new stdClass();
		$this->mail->theme = $this->mail->themedir = $this->mail->template = null;

		$current_theme = $this->theme->current_theme;

	//¤ find the theme and themedir ¤//
		$this->mail->theme 	= $this->theme->themes[$this->theme->current_theme]['Template'];
		$this->mail->themedir	= $this->theme->themes[$this->theme->current_theme]['Template Dir'];
		if (isset($this->args->Theme))
		{
			$x = $this->theme->get_theme_by_template($this->args->Theme);
			if (!empty($x))
			{
				$this->theme->current_theme 	= $x['Name'];
				$this->mail->theme		= $this->args->Theme;
				$this->mail->themedir		= $x['Template Dir'];
			}
			else
				if (isset($this->trace) && !empty($this->args->Theme)) $this->trace->log(sprintf('MAILPRESS [WARNING] - Missing theme : >> %1$s << Type : %2$s ', $this->args->Theme, $type), $this->trace->levels[512]);
		}

	//¤ find the templates ¤//
		$pt 			= ($this->build->plaintext) ? $this->theme->get_page_plaintext_templates_from($this->mail->theme) : $this->theme->get_page_templates_from($this->mail->theme);
		$this->build->dir = ABSPATH . $this->theme->themes[$this->theme->current_theme] [ ( ($this->build->plaintext) ? 'Plaintext Template Dir' : 'Template Dir' ) ] . '/' ;

	//¤ find the template ¤//
		if (isset($this->args->Template)) 
		{		
			if (isset($pt[$this->args->Template]))
			{
				$template_file = $pt[$this->args->Template][0];
				$this->mail->template = $this->args->Template;
			}
			else
				if (isset($this->trace) && (!$this->build->plaintext)) $this->trace->log(sprintf('MAILPRESS [NOTICE] - Missing template : >> %1$s << Folder : %2$s ', $this->args->Template, $this->build->dir), $this->trace->levels[512]);
		}

		$fname = $this->build->dir . $template_file;

		if ($this->build->plaintext && !is_file($fname))
		{
			$this->theme->current_theme = 'plaintext';
			$x = $this->theme->get_theme_by_template('plaintext');
			if (empty($x))
			{
				if (isset($this->trace)) $this->trace->log('MAILPRESS [ERROR] - Missing theme : plaintext theme has been deleted.', $this->trace->levels[512]);
				$this->theme->current_theme = $current_theme;
				unset($this->build);
				return '';
			}
			$template_file = 'default.php';
			$pt = $this->theme->get_page_templates_from('plaintext');
			$this->build->dir = ABSPATH . $x['Template Dir'] . '/' ;
			if (isset($this->args->Template)) 
			{		
				if (isset($pt[$this->args->Template]))
				{
					$template_file = $pt[$this->args->Template][0];
					$this->mail->template = $this->args->Template;
				}
			}
			$fname = $this->build->dir . $template_file;
		}

		if (is_file($fname)) $content = '<?php $this->load_template(\'' . $fname . '\'); ?>';

	//¤ find the functions.php ¤//
		$fname = $this->build->dir . 'functions.php';
		if (is_file($fname)) $content = "<?php require_once ('$fname'); ?>" . $content;

	//¤ build the mail ¤//
		$x = $this->do_eval($content);

		$this->theme->current_theme = $current_theme;
		unset($this->build);
		return ( '<br />' == trim($x) ) ? '' : trim($x);
	}

	function do_eval($x)
	{
		$x = 'global $posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID; ?>' . "\n $x";
		ob_start();
			echo(eval($x));
			$r = ob_get_contents();
		ob_end_clean();
		return $r;
	}


////  Send it  ////


	function swift_processing()
	{
		require_once (MP_ABSPATH . 'mp-includes/Swiftmailer/swift_required.php');

	//¤ Swift message ¤//
		try 
		{
			$this->build_swift_message();
		}
		catch (Swift_SwiftException $e) 
		{
			$this->trace->log('SWIFTMAILER [ERROR] - ' . "There was an unexpected problem building the mail:\n\n" . $e->getMessage() . "\n\n");	
			return false;
		}

	//¤ Swift connection ¤//
		try 
		{
			add_filter('MailPress_Swift_Connection_SMTP', 	array(__CLASS__, 'SMTP_connect'), 8, 2);

			$Swift_Connection_type = apply_filters('MailPress_Swift_Connection_type', 'SMTP');

			$conn = apply_filters('MailPress_Swift_Connection_' . $Swift_Connection_type , null, $this->trace );

			$this->swift = Swift_Mailer::newInstance($conn);
		}
		catch (Swift_SwiftException $e) 
		{
			$this->trace->log('SWIFTMAILER [ERROR] - ' . "There was a problem connecting with $Swift_Connection_type :\n\n" . $e->getMessage() . "\n\n");	
			$this->mysql_connect("MP_Mail connect error :  $Swift_Connection_type");
			return false;
		} 

		Swift_Preferences::getInstance()->setTempDir(MP_ABSPATH . "tmp")->setCacheType('disk');

	//¤ Swift sending ... ¤//
		try 
		{
			$this->swift = apply_filters('MailPress_PopBeforeSmtp', $this->swift);
		//¤ batch processing ¤//
			if ($this->mail->mailpress_batch_send)
				return apply_filters('MailPress_swift_send', $this);

			$this->mysql_disconnect(__CLASS__);

		//¤ swift batchSend ¤//
			if ($this->mail->swift_batchSend)
			{
				$this->swift->registerPlugin(new Swift_Plugins_DecoratorPlugin($this->row->replacements));
				if (!$this->swift->batchSend($this->message))
				{
					$this->mysql_connect('MP_Mail batchSend');
					return false;
				}
			}

		//¤ swift send ¤//
			else
			{
				if (!$this->swift->send($this->message)) 
				{
					$this->mysql_connect('MP_Mail send');
					return false;
				}
			}

			$this->mysql_connect(__CLASS__);

			return true;
		}
		catch (Swift_SwiftException $e) 
		{
			$this->trace->log('SWIFTMAILER [ERROR] - ' . "There was a problem sending with $Swift_Connection_type :\n\n" . $e->getMessage() . "\n\n");	
			$this->mysql_connect("MP_Mail sending error :  $Swift_Connection_type");
			return false;
		}
		return true;
	}

	function build_swift_message()
	{

	//¤ charset ¤//
		Swift_Preferences::getInstance()->setCharset($this->row->charset);

	//¤ message ¤//
		$this->message 	 = Swift_Message::newInstance();

		//$this->message->setLanguage(substr(WPLANG, 0, 2));
		if (self::get8BitEncoding) $this->message->setEncoder(Swift_Encoding::get8BitEncoding());

	//¤ from ¤//
		$this->message->setFrom(array($this->row->fromemail => $this->row->fromname));

	//¤ to & replacements ¤//
		if (!$this->mail->swift_batchSend) 
		{
			try
			{
				$this->message->addTo($this->row->toemail, $this->row->toname);
			}
			catch (Swift_RfcComplianceException $e)
			{
				$this->trace->log('SWIFTMAILER [ERROR] - ' . "Recipient do not comply RFC rules (discarded) :\n\n" . $e->getMessage() . "\n\n");
				return false;
			}
			//$this->message->setTo(array($this->row->toemail => $this->row->toname));
		}
		else
		{
			foreach($this->row->recipients as $toemail => $toname)
			{
				try
				{
					$this->message->addTo($toemail, $toname);
				}
				catch (Swift_RfcComplianceException $e)
				{
					$this->trace->log('SWIFTMAILER [WARNING] - ' . "Recipient do not comply RFC rules (discarded) :\n\n" . $e->getMessage() . "\n\n");	
					unset($this->row->replacements[$toemail]);
				}
			}
			//$this->message->setTo($this->row->recipients);
			foreach($this->row->replacements as $k => $v) $this->row->replacements[$k] = array_merge($this->mail->replacements, $this->row->replacements[$k]);
		}

	//¤ subject ¤//
		$this->message->setSubject($this->row->subject);

	//¤ filter headers ¤//
		$this->message	= apply_filters('MailPress_swift_message_headers', $this->message, $this->row);

	//¤ html ¤//
		if ($this->row->html)
		{
			$this->message->setBody($this->process_img( $this->row->html, $this->row->themedir ), 'text/html');
		}

	//¤ plaintext ¤//
		if ($this->row->plaintext)
		{
			if ($this->row->html)
				$this->message->addPart($this->row->plaintext);
			else
				$this->message->setBody($this->row->plaintext);
		}


	//¤ attachements ¤//
		$metas = MP_Mailmeta::has( $this->row->id, '_MailPress_attached_file');
		if ($metas)
		{
			foreach($metas as $meta)
			{
				$meta_value = unserialize( $meta['meta_value'] );
				if (!is_file($meta_value['file_fullpath'])) continue;
				$this->message->attach(Swift_Attachment::fromPath($meta_value['file_fullpath'], $meta_value['mime_type'])->setFilename($meta_value['name']));
			}
		}
	}

	function process_img($html, $path, $dest='mail')
	{
		$x		= $matches = $imgtags = array();
		$masks 	= array ('', $path . '/images/', $path . '/');

		$keepurl	= apply_filters('MailPress_img_mail_keepurl', false);

		$siteurl 	= get_option('siteurl') . '/';
		$fprefix 	= ('mail' == $dest) ? ABSPATH : $siteurl;

		$output = preg_match_all('/<img[^>]*>/Ui', $html, $imgtags, PREG_SET_ORDER); // all img tag

		if (empty($imgtags)) return $html;

		foreach ($imgtags as $imgtag)
		{
			$output = preg_match_all('/src=[\'"]([^\'"]+)[\'"]/Ui', $imgtag[0], $src, PREG_SET_ORDER); // for src attribute
			$matches[] = array(0 => $imgtag[0], 1 => $src[0][1]);
		}

		$imgs = array();
		foreach ($matches as $match)
		{
			$f = $u = false;
            
			if (!MailPress::is_image($match[1])) continue;

			if ( $keepurl && stristr($match[1], $siteurl) )
			{
				$imgs[$match[1]] = $match[1];
				continue;
			}
			elseif (stristr($match[1], $siteurl)) $u = true;
			elseif ((stristr($match[1], 'http://')) || (stristr($match[1], 'https://')))
			{
				$imgs[$match[1]] = $match[1];
				continue;
			}

			foreach ($masks as $mask)
			{
				if ($u) 	$file = str_ireplace($siteurl, '', $match[1]);
				else		$file = $mask . $match[1];

				if (is_file(ABSPATH . $file)) 
				{
					$f = true;
					$x[$match[1]] = $fprefix . $file;		// we can have the src/url image in different img tags ... so we embed it one time only
					if (isset($this->trace)) if ('mail' == $dest) $this->trace->log('MAILPRESS [NOTICE] - Image found : ' . $file, $this->trace->levels[512]);
				}
				if ($f) break;
				
			}
		}

		if ('mail' == $dest)
		{
			foreach ($x as $key => $file)
			{
				try 
				{
					$imgs[$key] = $this->message->embed(Swift_Image::fromPath($file));
				}
				catch (Swift_SwiftException $e) 
				{
					if (isset($this->trace)) $this->trace->log('SWIFTMAILER [ERROR] - ' . "There was a problem with this image: $file \n\n" . $e->getMessage() . "\n\n");
				} 
			}
		}
		else
		{
			foreach ($x as $key => $file)
			{
				$imgs[$key] = $file;
			}
		}
		foreach ($matches as $match)
		{
			$match[3]	= (isset($imgs[$match[1]])) ? str_replace($match[1], $imgs[$match[1]], $match[0]) : $match[0]; // and we retrieve it now with the proper <img ... />
			if ('html' != $dest) $match[3] = apply_filters('MailPress_img_mail', $match[3]); // apply_filters for 'mail', 'draft'
			$html		= str_replace($match[0], $match[3], $html);
		}
		return $html;
	}

////	MP_Mail SMTP functions	////

	public static function SMTP_connect($x, $y)
	{
		$connection_smtp = get_option(self::option_name_smtp);

		$conn = Swift_SmtpTransport::newInstance();

		$conn->setHost($connection_smtp['server']);
		$conn->setPort($connection_smtp['port']);

		if (!empty($connection_smtp ['ssl']))
			$conn->setEncryption($connection_smtp ['ssl']);

		if (empty($connection_smtp['username']) && empty($connection_smtp['password']))
			$y->log("**** Empty user/password for SMTP connection ****");	
		else
			$conn->setUsername($connection_smtp ['username']);
			$conn->setPassword($connection_smtp ['password']);

		// eventually popb4smtp (other authentications are detected automatically)
		if (isset($connection_smtp['smtp-auth']) && (!empty($connection_smtp['smtp-auth'])))
		{
			switch ($connection_smtp['smtp-auth'])
			{
				case '@PopB4Smtp' :
					add_filter('MailPress_PopBeforeSmtp', array(__CLASS__, 'MailPress_PopBeforeSmtp'), 8, 1);
				break;
			}
		}

		return $conn;
	}

	public static function MailPress_PopBeforeSmtp($_this_swift)
	{
		$connection_smtp = get_option(self::option_name_smtp);
		$_this_swift->registerPlugin(new Swift_Plugins_PopBeforeSmtpPlugin($connection_smtp['pophost'], $connection_smtp['popport']));
		return $_this_swift;
	}


/// DISPLAYING E-MAILS & NAMES ///


	function get_replacements($id, $main_id = false, $mp_user_id = false)
	{
		$mail_r = MP_Mailmeta::get( $id, '_MailPress_replacements' );
		if (!$mail_r && $main_id) $mail_r = MP_Mailmeta::get( $main_id, '_MailPress_replacements' );
		if (!$mail_r) $mail_r = array();

		if (!$mp_user_id) return $mail_r;

		$mail = MP_Mails::get($id);

		if (!$mail)  return $mail_r;
		if (is_email($mail->toemail))  return $mail_r;

		$mail->toemail = unserialize($mail->toemail);

		$mp_user = MP_Users::get($mp_user_id);

		if (!$mp_user) return $mail_r;
		if (!isset($mail->toemail[$mp_user->email])) return $mail_r;

		return array_merge($mail_r, $mail->toemail[$mp_user->email]);
	}

	function viewsubject($subject, $id, $main_id, $mp_user_id = false)
	{
		$replacements = $this->get_replacements($id, $main_id, $mp_user_id);
		foreach($replacements as $k => $v) 
			$subject = str_replace($k, $v, $subject, $ch);
		return $subject;
	}

	function viewhtml($id, $main_id, $theme = false, $template = false, $mp_user_id = false)
	{
		$x = MP_Mails::get($id);
		$y = array('sent', 'unsent', 'archived');
		if (!in_array($x->status, $y))
		{
			$this->args 		= new stdClass();
			$this->args->id		= $id;
			$this->args->main_id	= $main_id;
			if ($theme) $this->args->Theme = $theme;
			if ($template) $this->args->Template = $template;
			if (!isset($this->row)) $this->row = new stdClass();
			$this->row->subject 	= $x->subject;
			$this->args->html 	= $x->html;
			$x->html 			= $this->build_mail_content('html', 'the_content');
			$x->themedir 		= $this->mail->themedir;
		}
		$html = $this->process_img($x->html, $x->themedir, 'draft');

		$replacements = $this->get_replacements($id, $main_id, $mp_user_id);

		foreach($replacements as $k => $v) 
			$html	= str_replace($k, $v, $html, $ch);

		echo $html;
	}

	function viewplaintext($id, $main_id, $theme = false, $template = false, $mp_user_id = false)
	{
		$x = MP_Mails::get($id);
		$y = array('sent', 'unsent', 'archived');
		if (!in_array($x->status, $y))
		{
			$this->args 		= new stdClass();
			$this->args->id		= $id;
			$this->args->main_id	= $main_id;
			if ($theme) $this->args->Theme = $theme;
			if ($template) $this->args->Template = $template;
			if (!isset($this->row)) $this->row = new stdClass();
			$this->row->subject 	= $x->subject;
			$this->args->plaintext 	= $x->plaintext;
			$x->plaintext 		= $this->build_mail_content('plaintext');
		}
		$x->plaintext = htmlspecialchars($x->plaintext, ENT_NOQUOTES);

		$replacements = $this->get_replacements($id, $main_id, $mp_user_id);
		foreach($replacements as $k => $v) 
			$x->plaintext = str_replace($k, $v, $x->plaintext, $ch);

		include MP_ABSPATH . 'mp-includes/html/plaintext.php';
	}


/// DISPLAYING E-MAILS & NAMES ///


	public static function display_name($name, $for_mail = true)
	{
		$default = '_';
		if ( is_email($name) )	$name = trim(str_replace('.', ' ', substr($name, 0, strpos($name, '@'))));
		if ( $for_mail ) 
		{ if ( empty($name) ) 	$name = $default; }
		else
		{ if ($default == $name) $name = '';}
		return $name;									
	}
}