
** <?php if (isset($_the_title)) echo $_the_title; else $this->the_title(); ?> **
<?php echo mysql2date('F j, Y', current_time('mysql')); ?>


<?php if (isset($_the_content)) echo $_the_content; else $this->the_content(); ?>

<?php if (isset($_the_actions)) echo "$_the_actions"; ?>

