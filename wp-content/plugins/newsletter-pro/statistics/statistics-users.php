<?php
@include_once dirname(__FILE__) . '/../commons.php';

$options = stripslashes_deep($_POST['options']);

$newsletter_lists = get_option('newsletter_lists');
$nc = new NewsletterControls($options);
$nc->errors($errors);
$nc->messages($messages);

$list_count = $wpdb->get_results("select count(if(list_1=1,1,null)) as l1, count(if(list_2=1,1,null)) as l2, count(if(list_3=1,1,null)) as l3,
count(if(list_4=1,1,null)) as l4, count(if(list_5=1,1,null)) as l5, count(if(list_6=1,1,null)) as l6, count(if(list_7=1,1,null)) as l7,
count(if(list_8=1,1,null)) as l8, count(if(list_9=1,1,null)) as l9
from " . $wpdb->prefix . "newsletter where status='C'");

$feed = $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where feed=1 and status='C'");
$total = $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'");
?>

<div class="wrap">

    <h2>Subscriber statistics</h2>
    <p>
        This panel shows how many subscribers there is in each list. A subscriber can be in any, one or more lists. Subscribers can edit
    their list subscriptions using a special link you can insert in emails (like a newsletter or a welcome email). The link is generated
    by Newsletter Pro when it finds the placeholder {profile_url}.
    </p>
    <p>
        This report is limited to confirmed subscribers.
    </p>

    <form action="" method="post">
            <?php wp_nonce_field(); ?>
        <h3>Statistics</h3>

        <table class="form-table">
            <tr valign="top">
                <th>Total</th>
                <td>
                    <?php echo $total; ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Feed by mail</th>
                <td>
                    <?php echo $feed; ?>
                </td>
            </tr>
            <?php for ($i=1; $i<=NEWSLETTER_LISTS_MAX; $i++) { ?>
            <?php $attr = 'l'.$i; ?>
            <tr valign="top">
                <th><?php echo $newsletter_lists['name_' . $i]; ?></th>
                <td>
                    <?php echo $list_count[0]->$attr; ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </form>


    <h3>Subscriptions over time</h3>
    <?php
    $list = $wpdb->get_results("select count(*) as c, date(created) as d from " . $wpdb->prefix . "newsletter where status='C' group by date(created) order by d desc");
    ?>
    <table class="widefat">
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
