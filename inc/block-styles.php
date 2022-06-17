<?php
/**
 * Block Styles
 */

if ( function_exists( 'register_block_style' ) ) {
	/**
	 * Register block styles.
	 */
	function mhesn_register_block_styles() {
		// Image: Borders.
		register_block_style(
			'core/image',
			array(
				'name'  => 'mhesn-border',
				'label' => esc_html__( 'Borders', 'mhesn' ),
			)
		);
	}
	add_action( 'init', 'mhesn_register_block_styles' );
}
