<?php
/**
 * Register custom block styles.
 * Learn More: https://developer.wordpress.org/reference/functions/register_block_style/
 *
 */

 namespace MY_THEME\inc\blockEditor;
/**
 * Register block styles.
 */
function register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'minimal' => __( 'Minimal', 'textdomain' ),
			'text'    => __( 'Text Only', 'textdomain' ),
		),
		'core/quote'  => array(
			'large' => __( 'Large', 'textdomain' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_filter( 'init', __NAMESPACE__ . '\register_block_styles', 10, 1 );
