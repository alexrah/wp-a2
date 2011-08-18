<?php
// If you want to translate or customize this theme, just copy the file inside the folder
// wp-content/plugins/newsletter-pro-custom/themes
// (create it if it does not exist) and the a new theme called "theme-1" will appear
// on autocompose menu. You can rename the file if you want.

$posts = new WP_Query();
$posts->query(array('showposts'=>4, 'post_status'=>'publish', 'cat'=>3));
?>
<table bgcolor="#c0c0c0" width="100%" cellpadding="20" cellspacing="0" border="0">
    <tr>
        <td align="center">
            <table width="500" bgcolor="#ffffff" align="center" cellspacing="10" cellpadding="0" style="border: 1px solid #666; text-align: justify;">
          <tr>
              <td>
                <a href="http://fellineimpianti.it/">
                    <img src="http://fellineimpianti.it/wp-content/themes/cora/scripts/timthumb.php?src=http://fellineimpianti.it/wp-content/uploads/2011/08/LogoFellineMaster1.png&amp;w=400&amp;zc=1" alt="Felline Impianti">
                </a>
              </td>
          </tr>
          <tr>
              <td style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999">
                  <br />Ultime installazioni Residenziali.<br /><br />
              </td>
          </tr>
          <tr>
              <td style="font-size: 14px; color: #666">
                  <p>Caro {name}, ecco un aggiornamento da <?php echo get_option('blogname'); ?>:</p>
              </td>
          </tr>
<?php
while ($posts->have_posts())
{
    $posts->the_post();
    $image = nt_post_image(get_the_ID());
?>
          <tr>
              <td style="font-size: 14px; color: #666">
                    <?php if ($image != null) { ?>
                    <img src="<?php echo $image; ?>" alt="picture" align="left"/>
                    <?php } ?>
                  <p><a href="<?php echo get_permalink(); ?>" style="font-size: 16px; color: #000; text-decoration: none"><?php the_title(); ?></a></p>

                  <?php the_excerpt(); ?>
              </td>
          </tr>
<?php
}
?>
          <tr>
              <td style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999">
                  Avete ricevuto questa email perche' il vostro indirizzo {email} e' stato registrato dal nostro servizio. Se volete potete <a href="{unsubscription_url}">annullare</a> la sottoscrizione in qualsiasi momento.
              </td>
          </tr>
      </table>
        </td>
    </tr>
</table>
