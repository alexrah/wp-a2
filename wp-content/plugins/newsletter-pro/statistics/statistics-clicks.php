<?php
@include_once dirname(__FILE__) . '/../commons.php';

$res = $wpdb->get_results("select distinct newsletter from " . $wpdb->prefix .
    "newsletter_stats order by newsletter");

$options = stripslashes_deep($_POST['options']);

$nc = new NewsletterControls($options);
$nc->errors($errors);
$nc->messages($messages);

$newsletters = array();
for ($i=0; $i<count($res); $i++) {
    $newsletters[$res[$i]->newsletter] = $res[$i]->newsletter;
}


if ($action == 'show') {
    $users = $wpdb->get_var($wpdb->prepare("select count(distinct newsletter_id) from " . $wpdb->prefix . "newsletter_stats where newsletter=%s", $options['name']));
    $total = $wpdb->get_var($wpdb->prepare("select count(*) from " . $wpdb->prefix . "newsletter_stats where newsletter=%s", $options['name']));
    $list = $wpdb->get_results($wpdb->prepare("select url, anchor, count(*) as number from " . $wpdb->prefix . "newsletter_stats where newsletter=%s group by url, anchor order by number desc", $options['name']));
}

if ($action == 'remove') {
    $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter_stats where newsletter=%s", $options['name']));
}
?>

<div class="wrap">

    <h2>Newsletter Statistics - Clicks</h2>

    <form action="" method="post">
    <?php wp_nonce_field(); ?>
        
        <table class="form-table">
            <tr valign="top">
                <th>Newsletter</th>
                <td>
                    <?php if (empty($newsletters)) { ?>
                        No statistics found
                    <?php } else { ?>
                        <?php $nc->select('name', $newsletters); ?>
                        <?php $nc->button('show', 'Show'); ?>
                        <?php $nc->button('remove', 'Remove'); ?>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>

    <?php if ($action == 'show') { ?>

    <h3>Overwiew</h3>
    <table class="form-table">
        <tr valign="top">
            <th>Total number of clicks</th>
            <td><?php echo $total; ?></td>
        </tr>
        <tr valign="top">
            <th>Subscribers who clicked</th>
            <td><?php echo $users; ?></td>
        </tr>
    </table>


    <h3>Chart</h3>
        <?php
        $values = '';
        $labels = '';
        $partial = 0;
        for ($i=0; $i<min(count($list), 7); $i++) {
            $values .= $list[$i]->number . ',';
            $labels .= $i+1;
            $labels .= ' (' . ((int)($list[$i]->number/$total*100)) . ')%|';
            $partial += $list[$i]->number;
        }
        $labels .= 'Others (' . ((int)(($total-$partial)/$total*100)) . ')';
        $labels = urlencode($labels);
        $values .= $total-$partial;
        $values = urlencode($values);
        ?>
    <p>
        <img alt="chart" src="http://chart.apis.google.com/chart?chtt=Newsletter+<?php echo urlencode('"' . $_POST['name'] . '"'); ?>&cht=p3&chco=00ff00,0000ff&chs=600x300&chd=t:<?php echo $values; ?>&chl=<?php echo $labels; ?>" />
    </p>

    <h3>Clicks detail</h3>
    <table class="clicks" cellspacing="0">
        <tr>
            <th>Chart label</th>
            <th>Clicks</th>
            <th>Anchor</th>
            <th>URL</th>
        </tr>
            <?php
            if ($list) {
                for ($i=0; $i<count($list); $i++) {
                    $anchor = strip_tags($list[$i]->anchor, '<img>');
                    echo '<tr>';
                    echo '<td>' . ($i+1) . '</td>';
                    echo '<td>' . $list[$i]->number . '</td>';
                    echo '<td>' . $anchor . '</td>';
                    echo '<td>' . htmlspecialchars($list[$i]->url) . '</td>';
                    echo '</tr>';
                }
            }
            ?>
    </table>
    <?php } ?>
    
</div>
