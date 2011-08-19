<?php
$url_parms = MP_AdminPage::get_url_parms();

$h2 = __('Logs', MP_TXTDOM);

//
// MANAGING RESULTS/SUBSUBSUB URL/PAGINATION/
//

if( !isset($_per_page) || $_per_page <= 0 ) $_per_page = 20;
$url_parms['apage'] = isset($url_parms['apage']) ? $url_parms['apage'] : 1;
do
{
	$start = ( $url_parms['apage'] - 1 ) * $_per_page;

	list($_logs, $total, $subsubsub_urls) = MP_AdminPage::get_list($start, $_per_page, $url_parms);

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

$files 		= array_slice($_logs, 0, $_per_page);

//
// MANAGING MESSAGE / CHECKBOX RESULTS
//

$results = array(	'deleted'	=> array('s' => __('%s file deleted', MP_TXTDOM), 'p' => __('%s files deleted', MP_TXTDOM)),
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
// MANAGING BULK ACTIONS
//

$bulk_actions[''] 	= __('Bulk Actions');
$bulk_actions['delete']	= __('Delete', MP_TXTDOM);

?>
<div class='wrap'>
	<div id="icon-mailpress-tools" class="icon32"><br /></div>
	<h2>
		<?php echo esc_html( $h2 ); ?> 
<?php if ( isset($url_parms['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_attr( $url_parms['s'] ) ); ?>
	</h2>
<?php if (isset($message)) MP_AdminPage::message($message); ?>

	<ul class='subsubsub'><?php echo $subsubsub_urls; ?></ul>

	<form id='posts-filter' action='' method='get'>
		<input type='hidden' name='page' value='<?php echo MP_AdminPage::screen; ?>' />
		<?php MP_AdminPage::post_url_parms((array) $url_parms); ?>

		<p class='search-box'>
			<input type='text' name='s' value="<?php if (isset($url_parms['s'])) echo esc_attr( $url_parms['s'] ); ?>" class="search-input" />
			<input type='submit' value="<?php _e( 'Search', MP_TXTDOM ); ?>" class='button' />
		</p>

<?php
if ($files) {
?>
		<div class='tablenav'>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions); ?>
			</div>

<?php if ( $page_links ) echo "\n<div class='tablenav-pages'>$page_links</div>\n"; ?>

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
			<tbody id='the-file-list' class='list:file'>
<?php	foreach ($files as $file) MP_AdminPage::get_row( $file, $url_parms ); ?>
			</tbody>
		</table>
		<div class='tablenav'>
<?php 	if ( $page_links ) echo "			<div class='tablenav-pages'>$page_links</div>"; ?>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions, 'action2'); ?>
			</div>
			<br class='clear' />
		</div>
	</form>

	<form id='get-extra-files' method='post' action='' class='add:the-extra-file-list:' style='display: none;'>
<?php  MP_AdminPage::post_url_parms((array) $url_parms); ?>
<?php wp_nonce_field( 'add-file', '_ajax_nonce', false ); ?>
	</form>

	<div id='ajax-response'></div>

<?php
} else {
?>
	</form>
		<p>
			<?php (is_dir('../' . MP_AdminPage::get_path())) ? _e('No logs available', MP_TXTDOM) : printf( __('Wrong path : %s', MP_TXTDOM), '../' . MP_AdminPage::get_path() ); ?>
		</p>
<?php
}
?>
</div>