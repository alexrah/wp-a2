<?php
// The feed theme is a little complex because it leaves a great level of customization,
// but you need to be a PHP programmer!

global $newsletter, $post; // The newsletter plugin

// Now we query WordPress to have the latest posts. The query can be changed based on user
// data (accessible throught $newsletter->user) and can lead to no new posts so we can set
// the $newsletter->feed_skip to true and return to skip this user. It can be done everywhere
// in the theme, even after some output has been generated.
$posts = get_posts(array('showposts'=>10, 'post_status'=>'publish'));

// We'll cycle the posts below checking with newsletter_is_old() if the post is old for the current
// subscriber. Every subscriber contains a field (feed_time) which stores the last time we send her
// an update.
// It may happen that for specific subscriber there are no new post, so after the lopp there is a check
// to avoid to send an empty email. 

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title></title></head>
<body style="font-family: 'Trebuchet MS'; font-size: 14px;">

<?php echo $labels['intro']; ?>

<table width="550">
<?php
for($i=0; $i<count($posts); $i++) {
    $post = $posts[$i];
    // Setup the post (WordPress requirement)
    setup_postdata($post);
    // Is this post ($post) old for the current user ($newsletter_feed_user)?
    if ($newsletter->post_is_old()) {
        // If no post has been found for this subscribers, skip this email!
        if ($i == 0) {
            // Notify the feed by mail delivery engine to skip that user
            $newsletter->feed_skip = true;
            return;
        }
        // There is no more new post to send to this user, break the cycle to close the email
        break;
    }
    // Extract a thumbnail, return null if no thumb can be found
    $image = nt_post_image(get_the_ID());
?>
<tr>
    <td>
        <h3><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php if ($image != null) { ?>
            <img src="<?php echo $image; ?>" alt="nice photo" align="left" width="100" height="100" hspace="10"/>
        <?php } ?>
        <?php the_excerpt(); ?>
    </td>
</tr>
<?php
}
?>
</table>

<?php
// This is optional!
// There are some old posts already sent to the user? We make a list of "may be missed" articles
if ($i < count($posts)) {
?>

<h3><?php echo $labels['old_post']; ?></h3>
<table width="550">
    <tr>
        <td>
            <?php
            for(; $i<count($posts); $i++) {
                $post = $posts[$i];
                // Setup the post (WordPress requirement)
                setup_postdata($post);
            ?>
                <p><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></p>
            <?php
            }
            ?>
        </td>
    </tr>
</table>

<?php
} // if
?>

<?php
// This is optional too: we add some more things from our blog, in this example a
// tag cloud.
?>
<h3><?php echo $labels['tags']; ?></h3>
<table width="550">
    <tr>
        <td>
            <?php wp_tag_cloud(array('title_li'=>'', 'smallest'=>9, 'largest'=>14, 'unit'=>'px')); ?>
        </td>
    </tr>
</table>

<?php echo $labels['end']; ?>

<?php
// Never forget to give the user a way to unsubscribe the feed by mail service. There are some other
// links you may want to add, for example the {profile_url} link to let the subscriber to edit his
// data.
?>
<?php echo $labels['unsubscription']; ?>

</body>
</html>
