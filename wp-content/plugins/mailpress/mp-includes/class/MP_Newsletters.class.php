<?php
class MP_Newsletters
{
// for newsletters
	public static function register($newsletter = array())
	{
		if (empty($newsletter['id'])) return;

		global $mp_subscriptions, $mp_registered_newsletters;

		$newsletter['allowed'] 	= (isset($mp_subscriptions['newsletters'][$newsletter['id']]));
		$newsletter['default']  = (isset($mp_subscriptions['default_newsletters'][$newsletter['id']]));

		$mp_registered_newsletters[$newsletter['id']] = $newsletter;
	}

	public static function get($id) 
	{
		global $mp_registered_newsletters;
		if (!isset($mp_registered_newsletters[$id])) return false;
		return $mp_registered_newsletters[$id];
	}

	public static function get_all($lib = 'admin') 
	{
		global $mp_registered_newsletters;

		$x = array();
		foreach ($mp_registered_newsletters as $k => $v) $x[$k] = $v['descriptions'][$lib];
		ksort($x);

		return $x;
	}

	public static function get_active($lib = 'admin') 
	{
		global $mp_registered_newsletters;

		$x = array();
		foreach ($mp_registered_newsletters as $k => $v) if ($v['allowed']) $x[$k] = $v['descriptions'][$lib];
		ksort($x);

		return $x;
	}

	public static function get_active_by_scheduler($scheduler) 
	{
		global $mp_registered_newsletters;

		$x = array();
		foreach ($mp_registered_newsletters as $k => $v) if ($v['allowed'] && $scheduler == $v['scheduler']['id']) $x[$k] = $v;
		ksort($x);

		return $x;
	}

	public static function get_defaults()
	{
		global $mp_registered_newsletters;

		$x = array();
		foreach($mp_registered_newsletters as $n) if ($n['default']) $x[$n['id']] = $n['id'];
		ksort($x);

		return $x;
	}

	public static function get_templates() 
	{
		global $mp_registered_newsletters;

		$x = array();
		foreach ($mp_registered_newsletters as $k => $v) $x[] = $v['mail']['Template'];

		return array_unique($x);
	}

////  Object  ////

	public static function get_object_terms($mp_user_id = false) 
	{
		global $mp_registered_newsletters;

		$x = self::get_active();

		$a = ($mp_user_id) ? MP_Usermeta::get($mp_user_id, MailPress_newsletter::meta_key) : '';

		$y = (is_array($a)) ? array_flip($a) : ((empty($a)) ? array() : array($a => 1));

		foreach ($x as $k => $v)
		{
			if ( $mp_registered_newsletters[$k]['default'] &&  isset($y[$k])) unset($x[$k]);
			if (!$mp_registered_newsletters[$k]['default'] && !isset($y[$k])) unset($x[$k]);
		}
		return $x;
	}

	public static function set_object_terms( $mp_user_id, $object_terms = array() )
	{
		global $mp_registered_newsletters;
		$x = self::get_active();

		MP_Usermeta::delete($mp_user_id, MailPress_newsletter::meta_key);

		foreach ($x as $k => $v) 
		{
			$default = ( isset($mp_registered_newsletters[$k]['default']) && $mp_registered_newsletters[$k]['default'] );

			if     ( $default && !isset($object_terms[$k])) MP_Usermeta::add($mp_user_id, MailPress_newsletter::meta_key, $k);
			elseif (!$default &&  isset($object_terms[$k])) MP_Usermeta::add($mp_user_id, MailPress_newsletter::meta_key, $k);
		}
	}

	public static function reverse_subscriptions($id) 
	{
		global $wpdb;

		$mp_users = $wpdb->get_results( $wpdb->prepare( "SELECT mp_user_id AS id FROM $wpdb->mp_usermeta WHERE meta_key = %s AND meta_value = %s ;", MailPress_newsletter::meta_key, $id ) );

		$to_be_reversed = array();
		foreach($mp_users as $mp_user) $to_be_reversed[] = $mp_user->id;
		$not_in  = (empty($to_be_reversed)) ? '' : 'WHERE id NOT IN (' . join(', ', $to_be_reversed) . ')';

		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->mp_usermeta WHERE meta_key = %s AND meta_value =  %s ", MailPress_newsletter::meta_key, $id ) );
		$wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->mp_usermeta (mp_user_id, meta_key, meta_value) SELECT id, %s, %s FROM $wpdb->mp_users $not_in;", MailPress_newsletter::meta_key, $id ) );
	}

	public static function get_query_newsletter($id, $in = 'NOT') 
	{
		global $wpdb;
		return $wpdb->prepare( "SELECT DISTINCT a.id, a.email, a.name, a.status, a.confkey 
						FROM 	$wpdb->mp_users a 
						WHERE status = 'active' 
						AND 	$in EXISTS 	(
							SELECT DISTINCT b.mp_user_id 
							FROM 	$wpdb->mp_usermeta b 
							WHERE b.meta_key   = %s
							AND 	b.meta_value = %s 
							AND 	b.mp_user_id = a.id ) ;", MailPress_newsletter::meta_key, $id);
	}

//// *** ////

	public static function post_limits($limits) 
	{
		global $mp_general;

		if (isset($mp_general['post_limits']) && ($mp_general['post_limits'])) return 'LIMIT 0, ' . $mp_general['post_limits'];

		return $limits;
	}

	public static function send($newsletter, $qp = true, $mail = false, $trace = false)
	{
		if (!isset($newsletter['query_posts'])) return 'noqp';

		if (!$mail)
		{
			$in 	= ($newsletter['default']) ? 'NOT' : '';
			$mail	= new stdClass();
			$mail->recipients_query = self::get_query_newsletter($newsletter['id'], $in);
		}

		$rc = 'npst';

		if (isset($newsletter['mail']))
			foreach($newsletter['mail'] as $k => $v)
				if (!empty($newsletter['mail'][$k])) $mail->{$k} = $newsletter['mail'][$k];

		$mail->newsletter = $newsletter;

		add_filter('post_limits', array(__CLASS__, 'post_limits'), 8, 1);

		if ($qp)
		{
			query_posts($newsletter['query_posts']);
				while (have_posts()) { $qp = false; break; }	
			wp_reset_query();

		}
		if (!$qp)
		{
			query_posts($newsletter['query_posts']);
				$rc = MailPress::mail($mail);
			wp_reset_query();
		}

		remove_filter( 'post_limits', array(__CLASS__, 'post_limits'), 8, 1);

		return $rc;
	}
}