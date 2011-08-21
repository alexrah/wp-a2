<?php
$url_parms = MP_AdminPage::get_url_parms();

$h2 = __('Edit Mails', MP_TXTDOM);
$subtitle = '';

if (isset($url_parms['author'])) 
{
	$author_user = get_userdata( $url_parms['author'] );
	$subtitle .= ' ' . sprintf(__('by %s'), esc_html( $author_user->display_name ));
}

//
// MANAGING PAGINATION + SUBSUBSUB URL
//

if( !isset($_per_page) || $_per_page <= 0 ) $_per_page = 20;
$url_parms['apage'] = isset($url_parms['apage']) ? $url_parms['apage'] : 1;
do
{
	$start = ( $url_parms['apage'] - 1 ) * $_per_page;

	list($_mails, $total, $subsubsub_urls) = MP_AdminPage::get_list($start, $_per_page + 5, $url_parms); // Grab a few extra

	$url_parms['apage']--;		
} while ( $total <= $start );
$url_parms['apage']++;

$page_links = paginate_links	(array(	'base' => add_query_arg( 'apage', '%#%' ), 
							'format' => '', 
							'total' => ceil($total / $_per_page), 
							'current' => $url_parms['apage']
						)
					);
if ($url_parms['apage'] <= 1) unset($url_parms['apage']);

$mails 		= array_slice($_mails, 0, $_per_page);
$extra_mails 	= array_slice($_mails, $_per_page);

//
// MANAGING MESSAGE / CHECKBOX RESULTS
//

$results = array(	'deleted'	=> array('s' => __('%s mail deleted', MP_TXTDOM), 'p' => __('%s mails deleted', MP_TXTDOM)),
			'sent'	=> array('s' => __('%s mail sent', MP_TXTDOM),    'p' => __('%s mails sent', MP_TXTDOM)),
			'notsent'	=> array('s' => __('%s mail NOT sent', MP_TXTDOM),'p' => __('%s mails NOT sent', MP_TXTDOM)),
			'archived'	=> array('s' => __('%s mail archived', MP_TXTDOM),'p' => __('%s mails archived', MP_TXTDOM)),
			'unarchived'=> array('s' => __('%s mail unarchived', MP_TXTDOM),'p' => __('%s mails unarchived', MP_TXTDOM)),
			'saved'	=> array('s' => __('Mail saved', MP_TXTDOM),      'p' => __('Mail saved', MP_TXTDOM)),
);

foreach ($results as $k => $v)
{
	if (isset($_GET[$k]) && $_GET[$k])
	{
		if (!isset($message)) $message = '';
		$message .= sprintf( _n( $v['s'], $v['p'], $_GET[$k] ), $_GET[$k] );
		$message .=  '<br />';
	}
}

//
// MANAGING DETAIL/LIST URL
//

if (isset($url_parms['mode'])) $wmode = $url_parms['mode'];
$url_parms['mode'] = 'detail';
$detail_url = esc_url(MP_AdminPage::url( MailPress_mails, $url_parms ));
$url_parms['mode'] = 'list';
$list_url  	= esc_url(MP_AdminPage::url( MailPress_mails, $url_parms ));
if (isset($wmode)) $url_parms['mode'] = $wmode; 

//
// MANAGING BULK ACTIONS
//

$bulk_actions[''] = __('Bulk Actions');
if (isset($url_parms['status']))
{
	switch($url_parms['status'])
	{
		case 'draft' :
			$bulk_actions['send']		= __('Send', MP_TXTDOM);
		break;
		case 'sent' :
			$bulk_actions['archive']	= __('Archive', MP_TXTDOM);
		break;
		case 'archived' :
			$bulk_actions['unarchive']	= __('Unarchive', MP_TXTDOM);
		break;
	}
}
if (current_user_can('MailPress_delete_mails')) $bulk_actions['delete']  	= __('Delete', MP_TXTDOM);

?>
<div class='wrap'>
	<div id="icon-mailpress-mails" class="icon32"><br /></div>
	<div id='mp_message'></div>
	<h2>
		<?php echo esc_html( $h2 ); ?> 
		<a href='<?php echo MailPress_write; ?>' class="button add-new-h2"><?php echo esc_html(__('Add New')); ?></a> 
<?php if ( isset($url_parms['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_attr( $url_parms['s'] ) ); ?>
<?php if ( !empty($subtitle) )      echo    "<span class='subtitle'>$subtitle</span>"; ?>
	</h2>
<?php if (isset($message)) MP_AdminPage::message($message); ?>

	<form id='posts-filter' action='' method='get'>
		<input type='hidden' name='page' value='<?php echo MP_AdminPage::screen; ?>' />
		<?php MP_AdminPage::post_url_parms($url_parms, array('mode', 'status')); ?>

		<ul class='subsubsub'><?php echo $subsubsub_urls; ?></ul>

		<p class='search-box'>
			<input type='text' name='s' value="<?php if (isset($url_parms['s'])) echo esc_attr( $url_parms['s'] ); ?>" class="search-input" />
			<input type='submit' value="<?php _e( 'Search', MP_TXTDOM ); ?>" class='button' />
		</p>

<?php
if ($mails) {
?>
		<div class='tablenav'>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions); ?>
			</div>

<?php if ( $page_links ) echo "\n<div class='tablenav-pages'>$page_links</div>\n"; ?>

			<div class='view-switch'>
				<a href="<?php echo $list_url;   ?>"><img id="view-switch-list"    height="20" width="20" <?php if ( 'list'   == $url_parms['mode'] ) echo "class='current'" ?> alt="<?php _e('List View', MP_TXTDOM)   ?>" title="<?php _e('List View', MP_TXTDOM)   ?>" src="../wp-includes/images/blank.gif" /></a>
				<a href="<?php echo $detail_url; ?>"><img id="view-switch-excerpt" height="20" width="20" <?php if ( 'detail' == $url_parms['mode'] ) echo "class='current'" ?> alt="<?php _e('Detail View', MP_TXTDOM) ?>" title="<?php _e('Detail View', MP_TXTDOM) ?>" src="../wp-includes/images/blank.gif" /></a>
			</div>
			<br class="clear" />
		</div>
		<div class="clear"></div>

		<table class='widefat' cellspacing='0'>
			<thead>
				<tr>
<?php MP_AdminPage::columns_list(); ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
<?php MP_AdminPage::columns_list(false); ?>
				</tr>
			</tfoot>
			<tbody id='the-mail-list' class='list:mail'>
<?php foreach ($mails as $mail) 		MP_AdminPage::get_row( $mail->id, $url_parms ); ?>
			</tbody>
<?php if ($extra_mails) : ?>
			<tbody id='the-extra-mail-list' class='list:mail' style='display: none;'>
<?php foreach ($extra_mails as $mail) 	MP_AdminPage::get_row( $mail->id, $url_parms ); ?>
			</tbody>
<?php endif; ?>
		</table>
		<div class='tablenav'>
<?php if ( $page_links ) echo "\n<div class='tablenav-pages'>$page_links</div>\n"; ?>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions, 'action2'); ?>
			</div>
			<br class="clear" />
		</div>
	</form>

	<form id='get-extra-mails' method='post' action='' class='add:the-extra-mail-list:' style='display:none;'>
<?php MP_AdminPage::post_url_parms((array) $url_parms); ?>
<?php wp_nonce_field( 'add-mail', '_ajax_nonce', false ); ?>
	</form>

	<div id='ajax-response'></div>

<?php
} else {
?>
	</form>
	<div class="clear"></div>
	<p>
		<?php _e('No results found.', MP_TXTDOM) ?>
	</p>
<?php
}
?>
</div>