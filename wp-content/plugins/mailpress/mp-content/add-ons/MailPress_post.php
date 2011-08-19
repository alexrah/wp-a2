<?php
if (class_exists('MailPress') && !class_exists('MailPress_post'))
{
/*
Plugin Name: MailPress_post
Plugin URI: http://www.mailpress.org/wiki/index.php?title=Add_ons:Post
Description: This is just an add-on for MailPress to select posts for draft mails
Author: Roberto Morales O., Andre Renaut
Version: 5.1
Author URI: http://www.mailpress.org
*/

class MailPress_post
{
	const metakey  = '_MailPress_post_';
	const metakey_order  = '_MailPress_post-order';

	function __construct()
	{
// for wp admin
		if (is_admin())
		{
		// for role & capabilities
			add_filter('MailPress_capabilities', 		array(__CLASS__, 'capabilities'), 1, 1);
		// for mails list
			add_action('MailPress_get_icon_mails', 		array(__CLASS__, 'get_icon_mails'), 8, 1);
		// for meta box in write post
				add_action('do_meta_boxes', 				array(__CLASS__, 'add_meta_boxes_post'), 8, 3);
		// for meta box in write page
				add_action('MailPress_update_meta_boxes_write',	array(__CLASS__, 'update_meta_boxes_write'));
				add_filter('MailPress_scripts', 			array(__CLASS__, 'scripts'), 8, 2);
				add_action('MailPress_add_meta_boxes_write',	array(__CLASS__, 'add_meta_boxes_write'), 8, 2);
		}
// trash post
 		add_action('trash_post', 			array(__CLASS__, 'trash_post'), 8, 1);
// for ajax in write post
		add_action('wp_ajax_add-mpdraft',		array(__CLASS__, 'wp_ajax_add_mpdraft'));
		add_action('wp_ajax_delete-mpdraft',	array(__CLASS__, 'wp_ajax_delete_mpdraft'));
// for ajax in write mail
		add_action('mp_action_order_mppost',	array(__CLASS__, 'mp_action_order_mppost'));
		add_action('mp_action_delete_mppost',	array(__CLASS__, 'mp_action_delete_mppost'));

// template when posts
		add_filter('MailPress_draft_template', array(__CLASS__, 'draft_template'), 8, 2);
	}

////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////
////  ADMIN  ////

// for role & capabilities
	public static function capabilities($capabilities) 
	{
		$capabilities['MailPress_manage_posts'] = array(	'name'  => __('Posts', MP_TXTDOM), 
											'group' => 'mails'
		);
		return $capabilities;
	}

// for mails list
	public static function get_icon_mails($mail_id)
	{ 
		if (!MP_Posts::object_have_relations($mail_id)) return;
?>
			<img class='attach' alt="<?php _e('Posts', MP_TXTDOM); ?>" title="<?php _e('Posts', MP_TXTDOM); ?>" src='<?php echo get_option('siteurl') . '/' . MP_PATH; ?>mp-admin/images/post.png' />
<?php
	}

// trash post
	public static function trash_post($post_id)
	{ 
		MP_Posts::delete_post($post_id);
	}

// for meta box in write post  ////
	public static function add_meta_boxes_post($page, $type, $post)
	{
		if ('post' != $page) return;
		if ('side' != $type) return;
		if (!current_user_can('MailPress_manage_posts')) return;

		wp_register_script('mp-meta-box-post-drafts', 	'/' . MP_PATH . 'mp-includes/js/mp_meta_box_post_drafts.js', false, false, 1);
		wp_enqueue_script('mp-meta-box-post-drafts');

		wp_register_script( 'mp-thickbox', 		'/' . MP_PATH . 'mp-includes/js/mp_thickbox.js', array('thickbox'), false, 1);
		wp_enqueue_script('mp-thickbox');

		add_meta_box('MailPress_drafts', __('MailPress drafts', MP_TXTDOM), array(__CLASS__, 'meta_box_post'), 'post', 'side', 'core');
	}

	public static function meta_box_post($post) 
	{
		include (MP_ABSPATH . 'mp-includes/mp_meta_box_post_drafts.php');
	}

	public static function wp_ajax_add_mpdraft()
	{
		if (!current_user_can('MailPress_manage_posts')) MailPress::mp_die('-1');

		if ( !isset($_POST['post_id']) || !$_POST['post_id'] ) 
		{
			$x = new WP_Ajax_Response( array(	'what' => 'mpdraft', 
									'id' => new WP_Error( 'post_id', __('Post id unknown, save post first !', MP_TXTDOM) )
								   ) );
			$x->send();
		}

		$mpdraft = MP_Posts::insert( $_POST['newmpdraft'], $_POST['post_id'] );

		if ( is_wp_error($mpdraft) ) 
		{
			$x = new WP_Ajax_Response( array(	'what' => 'mpdraft', 
									'id' => $mpdraft
								  ) );
			$x->send();
		}

		$x = new WP_Ajax_Response( array(	'what' => 'mpdraft', 
								'id' => $_POST['newmpdraft'], 
								'data' => self::get_draft_row($_POST['newmpdraft'], stripslashes($_POST['newmpdraft_txt'])),
							  ) );
		$x->send();
		break;
	}

	public static function get_draft_row($id, $subject)
	{
		$edit_url = esc_url(MailPress_edit . "&id=$id");
		$title    = esc_attr(sprintf( __('Edit "%1$s"', MP_TXTDOM) , $subject ));
		$actions['edit'] = "<a href='$edit_url' title=\"$title\">$id</a>";

		$view_url = esc_url(add_query_arg( array('action' => 'iview', 'id' => $id, 'KeepThis' => 'true', 'TB_iframe' => 'true', 'width' => '600', 'height' => '400'), MP_Action_url ));
		$title    = esc_attr(sprintf( __('View "%1$s"', MP_TXTDOM) , $subject ));
		$actions['view'] = "<a href='$view_url' class='thickbox' title=\"$title\">$subject</a>";

		$delete_url = esc_url(MailPress::url( '#', array(), "delete-mpdraft_$id" ));
		$title      = esc_attr(__('Delete link', MP_TXTDOM));
		$actions['delete'] = "<a class='delete:mpdraftchecklist:mpdraft-{$id}' href='$delete_url' title=\"$title\"><img src='" . get_option('siteurl') . '/' . MP_PATH . "mp-admin/images/trash.png' alt='' /></a>";

		$out  = "<li id='mpdraft-{$id}' style='margin:0;'>\n";
		$out .= "\t<table class='widefat' style='background-color:transparent;'>\n";
		$out .= "\t\t<tr>\n";
		$out .= "\t\t\t<td style='width:10%;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['edit']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t\t<td style='width:100%;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['view']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t\t<td style='width:16px;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['delete']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t</tr>\n";
		$out .= "\t</table>\n";
		$out .= "</li>\n";
		return $out;
	}

	public static function wp_ajax_delete_mpdraft()
	{
		$x = MP_Posts::delete($_POST['id'], $_POST['post_id']);
		$x = ($x) ? $_POST['id'] : '-1';
		MailPress::mp_die($x);
	}

		
// for meta box in write page
	public static function update_meta_boxes_write()
	{
	}

	public static function scripts($scripts, $screen) 
	{
		if ($screen != MailPress_page_write) return $scripts;

		wp_register_script( 'mailpress_write_posts', '/' . MP_PATH . 'mp-admin/js/write_posts.js', array('jquery-ui-sortable', 'mp-lists'), false, 1);
		$scripts[] = 'mailpress_write_posts';

		return $scripts;
	}

	public static function add_meta_boxes_write($mail_id, $mp_screen)
	{
		if (!$mail_id) return;
		if (!current_user_can('MailPress_manage_posts')) return;

		if ( !MP_Posts::get_object_terms($mail_id) ) return;

		add_meta_box('write_posts', __('Posts', MP_TXTDOM), array(__CLASS__, 'meta_box'), MP_AdminPage::screen, 'normal', 'core');
	}
/**/
	public static function meta_box($mail)
	{
		$id = (isset($mail->id)) ? $mail->id : 0;
		$post_ids = MP_Posts::get_object_terms($id);
		if ( !$post_ids ) return;
?>
<div id='mpposts'>
	<div id='mppostchecklist' class='list:mppost'>
<?php		foreach ( $post_ids as $post_id ) echo self::get_post_row( $post_id ); ?>
	</div>
	<span id="mppost-ajax-response"></span>
</div>
<?php
	}

	//¤ for ajax
	public static function get_post_row( $id )
	{
                $_post = get_post($id);
                if (!$_post) continue;

		$delete_nonce = wp_create_nonce( 'delete-write-post_' . $id );

		$ptitle = $_post->post_title;

		$actions['sortable'] = "<img class='mppost-handle' style='cursor:move' alt='" . __('up/down', MP_TXTDOM) . "' title=\"" . esc_attr(__('up/down', MP_TXTDOM)) . "\" src='" . get_option('siteurl') . '/' . MP_PATH . "mp-admin/images/sortable.png' />";

		$edit_url = esc_url('post.php?action=edit&post=' . $id);
		$title    = esc_attr(sprintf( __('Edit "%1$s"', MP_TXTDOM) , $ptitle ));
		$actions['edit'] = "<a href='$edit_url' title=\"$title\">$id</a>";

		$view_url = $_post->guid;
		$title    = esc_attr(sprintf( __('View "%1$s"', MP_TXTDOM) , $ptitle ));
		$actions['view'] = "<a href='$view_url' target='_new' title=\"$title\">$ptitle</a>";

		$delete_url = esc_url(MailPress::url( '#', array(), "delete-mppost_$id" ));
		$title      = esc_attr(__('Delete link', MP_TXTDOM));
		$actions['delete'] = "<a class='delete:mppostchecklist:mppost-{$id}' href='$delete_url' title=\"$title\"><img src='" . get_option('siteurl') . '/' . MP_PATH . "mp-admin/images/trash.png' alt='' /></a>";

		$out  = "<div id='mppost-{$id}'>\n";
		$out .= "\t<table class='widefat' style='background-color:transparent;'>\n";
		$out .= "\t\t<tr>\n";
		$out .= "\t\t\t<td style='width:16px;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['sortable']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t\t<td style='width:10%;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['edit']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t\t<td style='width:100%;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['view']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t\t<td style='width:16px;border:none;'>\n";
		$out .= "\t\t\t\t{$actions['delete']}\n";
		$out .= "\t\t\t</td>\n";
		$out .= "\t\t</tr>\n";
		$out .= "\t</table>\n";
		$out .= "</div>\n";
		return $out;
	}

	public static function mp_action_order_mppost()
	{
            $mp_mail_id = $_POST['id'];
		$posts = explode(',', $_POST['posts']);
		foreach($posts as $post)
		{
			$post_id = str_replace('mppost-', '', $post);
			$meta_value[$post_id] = $post_id;
		}
		if (!MP_Mailmeta::add($mp_mail_id, self::metakey_order, $meta_value, true))
			MP_Mailmeta::update($mp_mail_id, self::metakey_order, $meta_value);
	}

	public static function mp_action_delete_mppost()
	{
		$x = MP_Posts::delete($_POST['mail_id'], $_POST['id']);
		$x = ($x) ? $_POST['id'] : '-1';
		MailPress::mp_die($x);
	}

// template when posts
	public static function draft_template($template, $main_id)
	{ 
		global $MP_post_ids, $mp_general;

		$MP_post_ids = MP_Posts::get_object_terms($main_id);
		if (empty($MP_post_ids)) return false;

		$query_posts = array('post__in' => $MP_post_ids, 'ignore_sticky_posts' => 1 );
		if (class_exists('MailPress_newsletter') && isset($mp_general['post_limits']) && !empty($mp_general['post_limits']))
			$query_posts['posts_per_page'] = $mp_general['post_limits'];

		add_filter('posts_orderby', 	array(__CLASS__, 'posts_orderby'), 8, 1);
		query_posts($query_posts);
		remove_filter('posts_orderby',array(__CLASS__, 'posts_orderby'));
		return 'manual';
	}

	public static function posts_orderby($orderby = '')
	{
		global $MP_post_ids;
		$orderby = ' FIELD(ID, ' . implode(',', $MP_post_ids) . ')';
		return $orderby;
	}
}
new MailPress_post();
}