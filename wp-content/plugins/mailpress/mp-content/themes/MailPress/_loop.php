<table <?php $this->classes('nopmb ctable'); ?> cellspacing='0' cellpadding='0'>
<?php while (have_posts()) : the_post(); ?>
	<tr>
		<td <?php $this->classes('nopmb ctd'); ?>>
			<div <?php $this->classes('cdiv'); ?>>
				<h2 <?php $this->classes('ch2'); ?>>
					<a <?php $this->classes('clink'); ?> href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
<?php the_title(); ?>
					</a>
				</h2>
				<small <?php $this->classes('nopmb cdate'); ?>>
<?php the_time('F j, Y') ?>
				</small>
				<div <?php $this->classes('nopmb'); ?>>
					<p <?php $this->classes('nopmb cp'); ?>>
<?php $this->the_content( __( '(more...)' ) ); ?>
					</p>
				</div>
			</div>
		</td>
	</tr>
<?php endwhile; ?>
</table>