<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * 
 *
 * @package mhesn
 */

?>
		</div><!-- .clear -->

	</div><!-- #content .site-content -->

	<div class="container clear">
	
		<footer id="colophon" class="site-footer">


			<?php if ( is_active_sidebar( 'footer' ) ) { ?>

				<div class="footer-columns clear">

					<?php dynamic_sidebar( 'footer' ); ?>															

				</div><!-- .footer-columns -->

			<?php } ?>

			<div class="clear"></div>
							
		</footer><!-- #colophon -->

		<div id="site-bottom" class="clear" style="background-color: black;">
			
			<div class="site-info" style="color: #fff;">

				<?php
					$mhesn_theme = wp_get_theme();
				?>

				&copy; <?php echo esc_html( date("o") ); ?> <?php echo esc_html( get_bloginfo('name') ); ?> - <a href="<?php echo esc_url( $mhesn_theme->get( 'AuthorURI' ) ); ?>"><?php esc_html_e('WordPress Theme', 'mhesn'); ?></a> <?php esc_html_e('by', 'mhesn'); ?> <a href="<?php echo esc_url( $mhesn_theme->get( 'AuthorURI' ) ); ?>"><?php esc_html_e('Mhesn', 'mhesn'); ?></a>

			</div><!-- .site-info -->

			<?php 
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'menu_class' => 'footer-nav' ) );
				}
			?>	

		</div><!-- #site-bottom -->

	</div><!-- .container -->

</div><!-- #page -->

<div id="back-top">
	<a href="#top" title="<?php esc_attr_e('Back to top', 'mhesn'); ?>"><span class="genericon genericon-collapse"></span></a>
</div>

<?php wp_footer(); ?>

</body>
</html>
