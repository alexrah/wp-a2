<?php
@include_once 'commons.php';

$users = $wpdb->get_var($wpdb->prepare("select count(distinct newsletter_id) from " . $wpdb->prefix . "newsletter_stats where email_id=%s", $_GET['id']));
$total = $wpdb->get_var($wpdb->prepare("select count(*) from " . $wpdb->prefix . "newsletter_stats where email_id=%s", $_GET['id']));
$list = $wpdb->get_results($wpdb->prepare("select url, anchor, count(*) as number from " . $wpdb->prefix . "newsletter_stats where email_id=%s group by url, anchor order by number desc", $_GET['id']));

if ($action == 'remove') {
    $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter_stats where newsletter=%s", $options['name']));
}
?>

<div class="wrap">

    <h2>Newsletter Statistics - Clicks</h2>

    <form action="" method="post">
    <?php wp_nonce_field(); ?>
        

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
    <?php if ($total != 0) { ?>

        <?php
        $values = '';
        $labels = '';
        $partial = 0;
        for ($i=0; $i<min(count($list), 10); $i++) {
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
    <?php } ?>
    
    <h3>Clicks detail</h3>
    <table class="widefat">
        <thead>
        <tr>
            <th>Chart label</th>
            <th>Clicks</th>
            <th>Anchor</th>
            <th>URL</th>
        </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
    </form>
    
</div>
