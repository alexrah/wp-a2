<?php
@include_once dirname(__FILE__) . '/../commons.php';

$options = stripslashes_deep($_POST['options']);

$nc = new NewsletterControls($options);
$nc->errors($errors);
$nc->messages($messages);

$list = $wpdb->get_results("select distinct name from " . $wpdb->prefix .
    "newsletter_profiles order by name");
$names = array();
for ($i=0; $i<count($list); $i++) {
    $names[$list[$i]->name] = $list[$i]->name;
}


if ($action == 'show') {
    $list = $wpdb->get_results($wpdb->prepare("select value, count(*) as total from " . $wpdb->prefix . "newsletter_profiles where name=%s group by value order by total desc", $options['name']));
    $total = $wpdb->get_var($wpdb->prepare("select count(*) from " . $wpdb->prefix . "newsletter_profiles where name=%s", $options['name']));
}
?>

<div class="wrap">

    <h2>Newsletter Statistics - Profiles</h2>

    <form action="" method="post">
            <?php wp_nonce_field(); ?>
        <h3>Statistics by profile</h3>

        <table class="form-table">
            <tr valign="top">
                <th>Profile field</th>
                <td>
                    <?php $nc->select('name', $names); ?>
                    <?php $nc->button('show', 'Show'); ?>
                </td>
            </tr>
        </table>
    </form>

        <?php if ($action == 'show') { ?>

        <p>Subscribers without such profile ARE NOT COUNTED!</p>
            <?php

            ?>

            <?php
            $values = '';
            $labels = '';
            for ($i=0; $i<min(count($list), 5); $i++) {
                $values .= $list[$i]->total . ',';
                if (strlen($list[$i]->value) > 10)
                    $labels .= $i+1;
                else
                    $labels .= $list[$i]->value;

                $labels .= ' (' . ((int)($list[$i]->total/$total*100)) . '%)|';
                $partial += $list[$i]->total;
            }
            $labels .= 'Others (' . ((int)(($total-$partial)/$total*100)) . ')';
            $labels = urlencode($labels);
            $values .= $total-$partial;
            $values = urlencode($values);
            ?>
        <p>
            <img src="http://chart.apis.google.com/chart?chtt=Subscribers+by+<?php echo urlencode('"' . $options['name'] . '"'); ?>&cht=p3&chco=ffff00,FF0000&chs=600x300&chd=t:<?php echo $values; ?>&chl=<?php echo $labels; ?>" />
        </p>


        <table class="clicks" cellspacing="0">
            <tr>
                <th>Chart label</th>
                <th>Field</th>
                <th>Total</th>
            </tr>
                <?php
                for ($i=0; $i<count($list); $i++) {

                    echo '<tr>';
                    echo '<td>' . ($i+1) . '</td>';
                    echo '<td>' . htmlspecialchars($list[$i]->value) . '</td>';
                    echo '<td>' . $list[$i]->total . '</td>';
                    echo '</tr>';
                }

            ?>
        </table>
        <br />

<?php } ?>

</div>
