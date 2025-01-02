<?php
/**
 * Enqueue scripts and styles.
 *

 */

namespace MY_THEME\inc;

/**
 * Enqueue scripts and styles.
 *
 */
function scripts() {
	$asset_file_path = \MY_THEME\ROOT_URL . '/build/__theme/js/index.asset.php';

	if ( is_readable( $asset_file_path ) ) {
		$asset_file = include $asset_file_path;
	} else {
		$asset_file = [
			'version'      => '0.1.0',
			'dependencies' => [ 'wp-polyfill' ],
		];
	}

	// Register styles & scripts.
	wp_enqueue_style( 'custom-styles', \MY_THEME\ROOT_URL . '/build/__theme/css/main.css', [], $asset_file['version'] );
	
	// Primary script with 
	wp_enqueue_script( 'custom-scripts', \MY_THEME\ROOT_URL . '/build/__theme/js/index.js', $asset_file['dependencies'], $asset_file['version'], true );
	
	// Add admin-ajax and nonce support
	wp_localize_script('custom-scripts', 'customData', [
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('custom_nonce'),
	]);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
