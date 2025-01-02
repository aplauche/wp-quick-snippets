<?php
/**
 * Register custom block category(ies).
 *
 */

 namespace MY_THEME\inc;

function register_category( $categories ) {
	$custom_block_category = [
		'slug'  => __( 'custom', 'textdomain' ),
		'title' => __( 'Bespoke Blocks', 'textdomain' ),
	];

	$categories_sorted    = [];
	$categories_sorted[0] = $custom_block_category;

	foreach ( $categories as $category ) {
		$categories_sorted[] = $category;
	}

	return $categories_sorted;
}

add_filter( 'block_categories_all', __NAMESPACE__ . '\register_category', 10, 1 );
