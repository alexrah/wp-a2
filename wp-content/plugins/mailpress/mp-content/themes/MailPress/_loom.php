<table <?php $this->classes('nopmb ctable'); ?> cellspacing='0' cellpadding='0'>
	<tr>
		<td <?php $this->classes('nopmb ctd'); ?>>
			<div <?php $this->classes('cdiv'); ?>>
				<h2 <?php $this->classes('ch2'); ?>>
<?php if (isset($_the_title)) echo $_the_title; else $this->the_title(); ?>
				</h2>
				<small <?php $this->classes('nopmb cdate'); ?>>
<?php echo mysql2date('F j, Y', current_time('mysql')); ?>
				</small>
				<div <?php $this->classes('nopmb'); ?>>
					<p <?php $this->classes('nopmb cp'); ?>>
<?php if (isset($_the_content)) echo $_the_content; else $this->the_content(); ?>
					</p>
					<p <?php $this->classes('nopmb cp'); ?>>
<?php echo (isset($_the_actions)) ? $_the_actions : '&nbsp;'; ?>
					</p>
				</div>
			</div>
		</td>
	</tr>
</table>