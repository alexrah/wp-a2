<?php while (have_posts()) : the_post(); ?>

<?php $this->the_title(); ?> [<?php the_permalink() ?>]
<?php the_time('F j, Y') ?>
<?php $this->the_content( ' (suite...)' ); ?>

<?php endwhile; ?>