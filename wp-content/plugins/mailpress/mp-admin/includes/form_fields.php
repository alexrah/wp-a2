<?php
$url_parms = MP_AdminPage::get_url_parms(array('s', 'apage', 'id', 'form_id'));

$form = MP_Forms::get($url_parms['form_id']);

$h2 = sprintf(__('Fields in form &#8220;%1$s&#8221;', MP_TXTDOM), $form->label);
$h2_preview_url = esc_url(MP_AdminPage::url(MP_Action_url, array('id' => $form->id, 'action' => 'ifview', 'KeepThis' => 'true', 'TB_iframe' => 'true', 'width' => '600', 'height' => '400')));

//
// MANAGING PAGINATION
//

if( !isset($_per_page) || $_per_page <= 0 ) $_per_page = 20;
$url_parms['apage'] = isset($url_parms['apage']) ? $url_parms['apage'] : 1;
do
{
	$start = ( $url_parms['apage'] - 1 ) * $_per_page;

	list($_fields, $total) = MP_AdminPage::get_list($start, $_per_page + 5, $url_parms); // Grab a few extra

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

$fields 		= array_slice($_fields, 0, $_per_page);
$extra_fields 	= array_slice($_fields, $_per_page);

//
// MANAGING MESSAGE
//

$messages[1] = __('Field added.', MP_TXTDOM);
$messages[2] = __('Field updated.', MP_TXTDOM);
$messages[3] = __('Field deleted.', MP_TXTDOM);
$messages[4] = __('Fields deleted.', MP_TXTDOM);
$messages[91] = __('Field not added.', MP_TXTDOM);
$messages[92] = __('Field not updated.', MP_TXTDOM);

if (isset($_GET['message']))
{
	$message = $messages[$_GET['message']];
	$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
}

//
// MANAGING CONTENT
//

$bulk_actions[''] 	= __('Bulk Actions');
$bulk_actions['delete']	= __('Delete', MP_TXTDOM);

// Form field types

$field_types = MP_Forms_field_types::get_all();

// Form templates

$form_templates = new MP_Forms_templates();
$xform_subtemplates = $form_templates->get_all_fields($form->template);

global $action;
wp_reset_vars(array('action'));
if ('edit' == $action) 
{
	$action = 'edited';
	$cancel = "<input type='submit' class='button' name='cancel' value=\"" . __('Cancel', MP_TXTDOM) . "\" />\n";

	$id = (int) $url_parms['id'];
	$field = MP_Forms_fields::get($id);

	$h3 = sprintf(__('Edit Form Field # %1$s', MP_TXTDOM), $id);
	$hb3= __('Update');
	$hbclass = '-primary';

// protected
	$disabled = '';
	if (isset($field->settings['options']['protected']) && $field->settings['options']['protected']) $disabled = " disabled='disabled'";
}
else 
{
	$action = MP_AdminPage::add_form_id;
	$cancel = '';

	$field = new stdClass();
	$field->type = 'text';

	$h3 = $hb3 = __('Add Form Field', MP_TXTDOM);
	$hbclass = '';

	$disabled = '';
}

$field->form_incopy = (isset($form->settings['visitor']['mail']) && ($form->settings['visitor']['mail'] != '0'));
?>
<div class='wrap nosubsub'>
	<div id='icon-mailpress-tools' class='icon32'><br /></div>
	<h2>
		<?php echo esc_html( $h2 ); ?> 
		<?php printf('<a href="%1$s" title="%2$s" class="thickbox button add-new-h2" >%3$s</a>', $h2_preview_url, esc_attr(sprintf(__('Form preview #%1$s (%2$s)', MP_TXTDOM), $form->id, $form->label)), esc_html(__('Preview', MP_TXTDOM))); ?>
<?php if ( isset($url_parms['s']) ) printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_attr( $url_parms['s'] ) ); ?>
	</h2>
<?php if (isset($message)) MP_AdminPage::message($message, ($_GET['message'] < 90)); ?>
	<form class='search-form topmargin' action='' method='get'>
		<input type='hidden' name='page' value='<?php echo MailPress_page_forms; ?>' />
		<input type='hidden' name='file' value='fields' />
		<?php MP_AdminPage::post_url_parms($url_parms, array('form_id')); ?>

		<p class='search-box'>
			<input type='text' name='s' value="<?php if (isset($url_parms['s'])) echo esc_attr( $url_parms['s'] ); ?>" class="search-input" />
			<input type='submit' value="<?php _e( 'Search', MP_TXTDOM ); ?>" class='button' />
		</p>

	</form>
	<br class='clear' />
	<div id='col-container'>
		<div id='col-right'>
			<div class='col-wrap'>	
				<form id='posts-filter' action='' method='get'>
					<input type='hidden' name='page' value='<?php echo MailPress_page_forms; ?>' />
					<input type='hidden' name='file' value='fields' />
<?php MP_AdminPage::post_url_parms($url_parms, array('s', 'apage', 'id', 'form_id')); ?>
					<div class='tablenav'>
<?php 	if ( $page_links ) echo "						<div class='tablenav-pages'>$page_links</div>"; ?>
						<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions); ?>
						</div>
						<br class='clear' />
					</div>
					<div class='clear'></div>
					<table class='widefat'>
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
						<tbody id='<?php echo MP_AdminPage::list_id; ?>' class='list:<?php echo MP_AdminPage::tr_prefix_id; ?>'>
<?php if ($fields) : ?>
<?php foreach ($fields as $_field) 		echo MP_AdminPage::get_row( $_field->id, $url_parms ); ?>
<?php endif; ?>
						</tbody>
<?php if ($extra_fields) : ?>
						<tbody id='<?php echo MP_AdminPage::list_id; ?>-extra' class='list:<?php echo MP_AdminPage::tr_prefix_id; ?>' style='display: none;'>
<?php
	foreach ($extra_fields as $_field)	echo MP_AdminPage::get_row( $_field->id, $url_parms ); ?>
						</tbody>
<?php endif; ?>
					</table>
					<div class='tablenav'>
<?php 	if ( $page_links ) echo "						<div class='tablenav-pages'>$page_links</div>\n"; ?>
						<div class='alignleft actions'>
<?php	MP_AdminPage::get_bulk_actions($bulk_actions, 'action2'); ?>
						</div>
						<br class='clear' />
					</div>
					<br class='clear' />
				</form>
			</div>
		</div><!-- /col-right -->
		<div id='col-left'>
			<div class='col-wrap'>
				<div class='form-wrap'>
					<h3><?php echo $h3; ?></h3>
					<div id='ajax-response'></div>
					<form name='<?php echo $action; ?>'  id='<?php echo $action; ?>'  method='post' action='' class='<?php echo $action; ?>:<?php echo MP_AdminPage::list_id; ?>: validate'>
						<input type='hidden' name='action'   value='<?php echo $action; ?>' />
<?php MP_AdminPage::post_url_parms($url_parms, array('id', 'form_id')); ?>
						<?php wp_nonce_field('update-' . MP_AdminPage::tr_prefix_id); ?>
						<div class="form-field form-required" style='margin:0;padding:0;'>
							<label for='field_label'><?php _e('Label', MP_TXTDOM); ?></label>
							<input name='label' id='field_label' type='text' value="<?php if (isset($field->label)) echo esc_attr($field->label); ?>" size='40' aria-required='true' />
							<p>&nbsp;</p>
						</div>
						<div class="form-field" style='margin:0;padding:0;'>
							<span style='float:right'>
								<span class='description'><small><?php _e('order in form', MP_TXTDOM); ?></small></span>
								<select name='ordre' id='field_ordre'>
<?php MP_AdminPage::select_number(1, 100, (isset($field->ordre)) ? $field->ordre : 1); ?>
								</select>
								<span class='description'><small><?php _e('sub template', MP_TXTDOM); ?></small></span>
								<select name='template' id='field_template'>
<?php MP_AdminPage::select_option($xform_subtemplates, (isset($field->template)) ? $field->template : ( (isset($xform_subtemplates[$field->type])) ? $field->type : 'standard' ) ); ?>
								</select>
							</span>
							<label for='field_description' style='display:inline;'><?php _e('Description', MP_TXTDOM); ?></label>
							<input name='description' id='field_description' type='text' value="<?php if (isset($field->description)) echo esc_attr($field->description); ?>" size='40' />
							<p><small><?php _e('The description can be use to give further explanations', MP_TXTDOM); ?></small></p>
						</div>
						<div>
							<label><?php _e('Type', MP_TXTDOM) ?></label>
							<table style='margin:1px;padding:3px;width:100%;-moz-border-radius: 5px;-webkit-border-radius: 5px;-khtml-border-radius: 5px;' class='bkgndc bd1sc'>
<?php
$col = 2;
$td = 0;
$tr = false;
foreach ($field_types as $key => $field_type)
{
	if (intval ($td/$col) == $td/$col ) echo "\t\t\t\t\t\t\t\t<tr>\n";
?>
									<td style='padding:0 5px 5px;'>
										<input type='radio' value='<?php echo $key; ?>' name='_type' id='field_type_<?php echo $key; ?>' class="field_type"<?php checked($key, $field->type); ?><?php if ( (!empty($disabled)) && ($key != $field->type) ) echo " disabled='disabled'"; ?> />
									</td>
									<td>
										<label for="field_type_<?php echo $key; ?>" class="field_type_<?php echo $key; ?>" style="padding-left:28px;margin-right:1em;display:inline;font-size:11px;"><?php echo $field_type['desc']; ?></label>
									</td>
<?php
	$td++;
	if (intval ($td/$col) == $td/$col ) echo "\t\t\t\t\t\t\t\t</tr>\n";
}
if (intval ($td/$col) != $td/$col ) while (intval ($td/$col) != $td/$col ) {echo "\t\t\t\t\t\t\t\t\t<td colspan='2'></td>\n"; ++$td; $tr = true;}
if ($tr) echo "\t\t\t\t\t\t\t\t</tr>\n";
?>
							</table>
						</div>
						<div id='form_fields_specs' style='margin-top:18px;'>
<?php foreach ($field_types as $key => $field_type) MP_Forms_field_types::settings_form($key, $field); ?>
						</div>
						<p class='submit'>
							<input type='submit' class='button<?php echo $hbclass; ?>' name='submit' id='form_submit' value="<?php echo $hb3; ?>" />
							<?php echo $cancel; ?>
						</p>
					</form>
				</div>
			</div>
		</div><!-- /col-left -->
	</div><!-- /col-container -->
</div><!-- /wrap -->