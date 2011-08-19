<?php
$url_parms = MP_AdminPage::get_url_parms();

$h2 = __('MailPress Add-ons', MP_TXTDOM);

//
// MANAGING SUBSUBSUB URL + LIST
//

list($addons, $subsubsub_urls) = MP_AdminPage::get_list(0, 0, $url_parms); // Grab a few extra

//
// MANAGING MESSAGE / CHECKBOX RESULTS
//

$results = array(	'activated'	=> array('s' => __('%s add-on activated', MP_TXTDOM),  'p' => __('%s add-ons activated', MP_TXTDOM)),
			'deactivated'=>array('s' => __('%s add-on deactivated', MP_TXTDOM),'p' => __('%s add-ons deactivated', MP_TXTDOM)),
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
// MANAGING CONTENT
//

$context = (isset($url_parms['status'])) ? $url_parms['status'] : false;

//
// MANAGING BULK ACTIONS
//

$bulk_actions[''] = __('Bulk Actions');
if ( 'active' != $context )		$bulk_actions['activate']   = __('Activate');
if ( 'inactive' != $context )		$bulk_actions['deactivate'] = __('Deactivate');

?>
<div class='wrap'>
	<div id="icon-mailpress-addons" class="icon32"><br /></div>
	<div id='mp_message'></div>
	<h2>
		<?php echo esc_html( $h2 ); ?> 
<?php if ( isset($url_parms['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_attr( $url_parms['s'] ) ); ?>
	</h2>
<?php if (isset($message)) MP_AdminPage::message($message); ?>

	<ul class='subsubsub'><?php echo $subsubsub_urls; ?></ul>

	<form id='posts-filter' action='' method='get'>

		<p class='search-box'>
			<input type='text' name='s' value="<?php if (isset($url_parms['s'])) echo esc_attr( $url_parms['s'] ); ?>" class="search-input" />
			<input type='submit' value="<?php _e( 'Search', MP_TXTDOM ); ?>" class='button' />
		</p>

		<input type='hidden' name='page' value='<?php echo MP_AdminPage::screen; ?>' />
		<?php MP_AdminPage::post_url_parms($url_parms, array('status')); ?>
<?php
if ($addons) {
?>
		<div class='tablenav'>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions); ?>
			</div>
			<br class="clear" />
		</div>
		<div class="clear"></div>

		<table class='widefat plugins' cellspacing='0'>
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
			<tbody class='addons'>
<?php foreach ($addons as $addon) 		MP_AdminPage::get_row( $addon, $url_parms ); ?>
			</tbody>
		</table>
		<div class='tablenav'>
			<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions, 'action2'); ?>
			</div>
			<br class="clear" />
		</div>
	</form>
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