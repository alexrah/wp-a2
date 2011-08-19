<?php
global $mp_general, $mp_subscriptions;
if (!isset($subscriptions)) $subscriptions = $mp_subscriptions;
if (!isset($subscriptions['default_newsletters'])) $subscriptions['default_newsletters'] = array();
?>
<tr valign='top'>
	<th style='padding:0;' ><strong><?php _e('Newsletters', MP_TXTDOM); ?></strong></th>
	<td style='padding:0;' colspan='4'></td>
</tr>
<tr valign='top'>
	<th scope='row'>
		<?php _e("Allow subscriptions to", MP_TXTDOM); ?>
		<input type='hidden'   name='newsletter[on]' value='on' />
	</th>
	<td colspan='4'></td>
</tr>
<?php
$col = 4;
$item  = 1;
$row = $col * $item;
$i = $j = $td = $tr = $alt = 0;

global $mp_registered_newsletters;

foreach ($mp_registered_newsletters as $mp_registered_newsletter)
{
	if (intval ($i/$row) == $i/$row ) 
	{
		$alt++;
		$alternate = (($alt/2) != intval($alt/2)) ? "class='bkgndc'" : '';
		$tr = true; 
		$td = 0;
		$blog = (isset($mp_registered_newsletter['mail']['the_category'])) ? '' : '_blog' ;
		$th   = (isset($mp_registered_newsletter['mail']['the_category'])) ? "<tr valign='top' $alternate><th scope='row'>" . $mp_registered_newsletter['mail']['the_category'] . "</th>\n" : "<tr valign='top' class='bkgndc bd1sc'><th scope='row'>" . __("** Blog **", MP_TXTDOM) . "</th>\n";
		echo $th;
	}
	if (intval ($j/$item) == $j/$item ) { echo "<td class='field' style=''>\n"; ++$td; }

	$default_style   = (isset($subscriptions['newsletters'][$mp_registered_newsletter['id']])) ? '' : " style='display:none;'" ;
	$default_checked = (isset($subscriptions['default_newsletters'][$mp_registered_newsletter['id']])) ? " checked='checked'" : '';
?>
		<label for='newsletter_<?php echo $mp_registered_newsletter['id'].$blog; ?>'>
			<input class='newsletter' id='newsletter_<?php echo $mp_registered_newsletter['id'].$blog; ?>' name='subscriptions[newsletters][<?php echo $mp_registered_newsletter['id']; ?>]' type='checkbox' <?php echo( (isset($subscriptions['newsletters'][$mp_registered_newsletter['id']])) ? "checked='checked'" : ''); ?> />
			&nbsp;<?php echo $mp_registered_newsletter['descriptions']['admin']; ?>
		</label>
		<br />
		<label for='default_newsletter_<?php echo $mp_registered_newsletter['id'].$blog; ?>'>
			<span id='span_default_newsletter_<?php echo $mp_registered_newsletter['id'].$blog; ?>'<?php echo $default_style; ?>>
				<input  id='default_newsletter_<?php echo $mp_registered_newsletter['id'].$blog; ?>' name='subscriptions[default_newsletters][<?php echo $mp_registered_newsletter['id']; ?>]' type='checkbox'<?php echo "$default_checked"; ?> />
				&nbsp;<?php _e('default', MP_TXTDOM); ?>
			</span>
		</label>
<?php
	$j++;
	if (intval ($j/$item) == $j/$item )  echo "</td>\n";
	$i++;
	if (intval ($i/$row) == $i/$row ) { echo "</tr>\n"; $tr = false; }
}
if (intval ($j/$item) != $j/$item )
{
	echo "</td>\n"; 
	while ($td < $item) {echo "<td></td>\n"; ++$td;}
}
if (intval ($i/$row) != $i/$row)  echo "</tr>\n";
?>
<tr valign='top' style='line-height:10px;padding:0;'><td colspan='5' style='line-height:10px;padding:0;'>&nbsp;</td></tr>
<tr valign='top' class='mp_sep' style='line-height:2px;padding:0;'><td colspan='5' style='line-height:2px;padding:0;'></td></tr>
<tr><th></th><td colspan='4'></td></tr>