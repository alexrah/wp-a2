<?php
if (!isset($test)) $test = get_option(MailPress::option_name_test);

$th = new MP_Themes();
$themes = $th->themes; 
if (empty($test['theme'])) $test['theme'] = $themes[$th->current_theme]['Template']; 

$xtheme = $xtemplates = array();
foreach ($themes as $theme)
{
	if ( 'plaintext' == $theme['Template'] ) continue;

	$xtheme[$theme['Template']] = $theme['Template'];
	$templates = $th->get_page_templates_from($theme['Template']);

	$xtemplates[$theme['Template']] = array();
	foreach ($templates as $key => $value)
	{
		$xtemplates[$theme['Template']][$key] = $key;
	}
	if (!empty($xtemplates[$theme['Template']])) ksort($xtemplates[$theme['Template']]);

	array_unshift($xtemplates[$theme['Template']], __('none', MP_TXTDOM));
}

$formname = substr(basename(__FILE__), 0, -4); 
?>
<form name='<?php echo $formname ?>' action='' method='post' class='mp_settings'>
	<input type='hidden' name='formname' value='<?php echo $formname ?>' />
	<table class='form-table'>
		<tr>
			<th><?php _e('To', MP_TXTDOM); ?></th>
			<td style='padding:0;'>
				<table class='subscriptions' cellspacing='0'>
					<tr>
						<td class='pr10<?php if (isset($toemailclass)) echo " $form_invalid"; ?>'>
							<?php _e('Email : ', MP_TXTDOM); ?> 
							<input type='text' size='25' name='test[toemail]' value="<?php if (isset($test['toemail'])) echo $test['toemail']; ?>" />
						</td>
						<td class='pr10<?php if (isset($tonameclass)) echo " $form_invalid"; ?>'>
							<?php _e('Name : ', MP_TXTDOM); ?> 
							<input type='text' size='25' name='test[toname]' value="<?php if (isset($test['toname'])) echo esc_attr($test['toname']); ?>" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th scope='row'>
				<?php _e("Advanced options", MP_TXTDOM); ?> 
			</th>
			<td> 
				<?php _e('Theme', MP_TXTDOM); ?>
				&nbsp;
				<select name='test[theme]'    id='theme'>
<?php MP_AdminPage::select_option($xtheme,$test['theme']);?>
				</select>
				&nbsp;
				<?php _e('Template', MP_TXTDOM); ?>
				&nbsp;
<?php 
foreach ($xtemplates as $key => $xtemplate)
{
	$xx = ( isset($test['theme'], $test['template']) && $key == $test['theme'] ) ? $test['template'] : '0';
?>
				<select name='test[th][<?php echo $key; ?>][tm]' id='<?php echo $key; ?>' class='<?php if ($key != $test['theme']) echo 'mask ';?>template'>
<?php MP_AdminPage::select_option($xtemplate, $xx);?>
				</select>
<?php
}
?>
				<br /><br />
<?php
$count = 0;
$checks = array('forcelog' => __('Log it', MP_TXTDOM), 'fakeit' => __('Send it', MP_TXTDOM), 'archive' => __('Archive it', MP_TXTDOM), 'stats' => __('Include it in statistics', MP_TXTDOM) );
foreach($checks as $k => $v) {
	$count++;
	echo "\t\t\t\t<input name='test[$k]' id='$k' type='checkbox' " . ( (isset($test[$k])) ? "checked='checked'" : '' ) . " />\n\t\t\t\t&nbsp;\n\t\t\t\t<label for='$k'>$v</label>\n";
	if ($count != count($checks)) echo "\t\t\t\t<br />\n";
}
?>
			</td>
		</tr>
	</table>
	<p class='submit'>
		<input class='button-primary' type='submit' name='Submit' value='<?php  _e('Save', MP_TXTDOM); ?>' />
		<input class='button-primary' type='submit' name='Test'   value='<?php  _e('Save &amp; Test', MP_TXTDOM); ?>' />
	</p>
</form>