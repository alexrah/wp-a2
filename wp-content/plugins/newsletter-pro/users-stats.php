<?php
    $all_count = $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter");
    $options_profile = get_option('newsletter_profile');
?>
<div class="wrap">

<h2>Users' statistics</h2>
<p>Counts are limited to confirmed subscribers.</p>

<h3>Overview</h3>
<table class="widefat" style="width: 300px;">
    <thead><tr><th>Status</th><th>Total</th></thead>
    <tr valign="top">
        <td>Any</td>
        <td>
            <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter"); ?>
        </td>
    </tr>
    <tr>
        <td>Confirmed</td>
        <td>
            <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'"); ?>
        </td>
    </tr>
    <tr>
        <td>Not confirmed</td>
        <td>
            <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='S'"); ?>
        </td>
    </tr>
    <tr>
        <td>Bounced<br /><small>Actually not supported</small></td>
        <td>
            <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='B'"); ?>
        </td>
    </tr>
</table>

<h3>Lists</h3>
<table class="widefat" style="width: 300px;">
    <thead><tr><th>List</th><th>Total</th></thead>
    <?php for ($i=1; $i<=9; $i++) { ?>
    <tr>
        <td><?php echo '(' . $i . ') ' . $options_profile['list_' . $i]; ?></td>
        <td>
            <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where list_" . $i . "=1 and status='C'"); ?>
        </td>
    </tr>
    <?php } ?>
</table>

<h3>Sex</h3>
<?php
    $male_count = $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where sex='m'");
    $female_count = $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where sex='f'");
    $other_count = ($all_count-$male_count-$female_count)
?>
<table class="widefat" style="width: 300px">
    <thead><tr><th>Sex</th><th>Total</th></thead>
    <tr><td>Male</td><td><?php echo $male_count; ?></td></tr>
    <tr><td>Female</td><td><?php echo $female_count; ?></td></tr>
    <tr><td>Not specified</td><td><?php echo $other_count; ?></td></tr>
</table>

<p>
    <img alt="chart" src="http://chart.apis.google.com/chart?chtt=Sex&chds=0,<?php echo $all_count; ?>&cht=p3&chco=00ff00,0000ff&chs=600x300&chd=t:<?php echo $male_count; ?>,<?php echo $female_count; ?>,<?php echo $other_count; ?>&chl=Male|Female|Other" />
</p>

    <h3>Subscriptions over time</h3>

    <h4>Subscriptions by month</h4>
    <?php
    $list = $wpdb->get_results("select count(*) as c, concat(year(created), '-', date_format(created, '%m')) as d from " . $wpdb->prefix . "newsletter where status='C' group by concat(year(created), '-', date_format(created, '%m')) order by d desc limit 24");
    $max = 0;
    for ($i=count($list)-1; $i>=0; $i--) {
        $month = $list[$i];
        $y .= $month->c . ','; $x .= substr($month->d, 5) . '|';
        $max = max($max, $month->c);
    }
    ?>
    <p>
        <img alt="chart" src="http://chart.apis.google.com/chart?chds=0,<?php echo $max; ?>&chtt=Subscription rate&cht=bvg&chco=00ff00,0000ff&chs=600x300&chd=t:<?php echo substr($y, 0, -1); ?>&chl=<?php echo substr($x, 0, -1); ?>" />
    </p>

    <table class="widefat" style="width: 300px">
        <thead>
        <tr valign="top">
            <th>Date</th>
            <th>Subscribers</th>
        </tr>
        </thead>
        <?php foreach ($list as $day) { ?>
        <tr valign="top">
            <td><?php echo $day->d; ?></td>
            <td><?php echo $day->c; ?></td>
        </tr>
        <?php } ?>
    </table>


    <h4>Subscriptions by day</h4>
    <?php
    $list = $wpdb->get_results("select count(*) as c, date(created) as d from " . $wpdb->prefix . "newsletter where status='C' group by date(created) order by d desc limit 365");
    ?>
    <table class="widefat" style="width: 300px">
        <thead>
        <tr valign="top">
            <th>Date</th>
            <th>Subscribers</th>
        </tr>
        </thead>
        <?php foreach ($list as $day) { ?>
        <tr valign="top">
            <td><?php echo $day->d; ?></td>
            <td><?php echo $day->c; ?></td>
        </tr>
        <?php } ?>
    </table>


</div>