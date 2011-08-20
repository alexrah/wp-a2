<!-- start footer -->
				</div>
<?php //$this->get_sidebar(); ?>
			</div>
			<div style='clear:both;'></div>
			<table <?php $this->classes('ftable'); ?> cellspacing='0' cellpadding='0'>
				<tr>	
					<td <?php $this->classes('frtd'); ?>>
						<b>
							<a href="http://fellineimpianti.it/">FELLINE IMPIANTI</a>: Via Clavesana, 56 - Andora - Tel.0182.684116 - <a href="http://fellineimpianti.it/?page_id=52">CONTATTO</a>
						</b>
          </td>	
          <td align="right" <?php $this->classes('fltd'); ?>>
						<b>
						  Gli Esperti della Climatizzazione,  al Vostro servizio.
						</b>
					</td>

				</tr>
			</table>
		</div>
<?php if (isset($this->args->unsubscribe)) { ?>
		<div <?php $this->classes('mail_link'); ?>>
			<a href='{{unsubscribe}}'  <?php $this->classes('mail_link_a'); ?>>Cambia le tue Impostazioni</a>
		</div>
<?php } ?>
		</div>
	</body>
</html>
