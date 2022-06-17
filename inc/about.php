<?php
/**
 * Mhesn Theme page
 *
 * @package mhesn
 */

function mhesn_about_admin_style( $hook ) {
	if ( 'appearance_page_mhesn-about' === $hook ) {
		wp_enqueue_style( 'mhesn-about-admin', get_theme_file_uri( 'assets/css/about-admin.css' ), null, '1.0' );
	}
}
add_action( 'admin_enqueue_scripts', 'mhesn_about_admin_style' );

/**
 * Add theme page
 */
function mhesn_menu() {
	add_theme_page( esc_html__( 'About mhesn', 'mhesn' ), esc_html__( 'About Mhesn', 'mhesn' ), 'edit_theme_options', 'mhesn-about', 'mhesn_about_display' );
}
add_action( 'admin_menu', 'mhesn_menu' );

/**
 * Display About page
 */
function mhesn_about_display() {
	$theme = wp_get_theme();
	?>
	<div class="wrap about-wrap full-width-layout">
		<h1><?php echo esc_html( $theme ); ?></h1>
		<div class="about-theme">
			<div class="theme-description">
				<p class="about-text">
					<?php
					// Remove last sentence of description.
					$description = explode( '. ', $theme->get( 'Description' ) );

					array_pop( $description );

					$description = implode( '. ', $description );

					echo esc_html( $description . '.' );
				?></p>
			</div>

			<div class="theme-screenshot">
				<img src="<?php echo esc_url( $theme->get_screenshot() ); ?>" />
			</div>

		</div>

	</div>
	<?php
}