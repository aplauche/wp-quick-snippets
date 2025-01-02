<?php

namespace MY_THEME\inc\setup;

/**
 * ACF Set custom load and save JSON points.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/
 */

add_filter( 'acf/json/load_paths', __NAMESPACE__ . '\json_load_paths' );
add_filter( 'acf/settings/save_json/type=acf-field-group', __NAMESPACE__ . '\json_save_path_for_field_groups' );
add_filter( 'acf/settings/save_json/type=acf-ui-options-page', __NAMESPACE__ . '\json_save_path_for_option_pages' );
add_filter( 'acf/settings/save_json/type=acf-post-type', __NAMESPACE__ . '\json_save_path_for_post_types' );
add_filter( 'acf/settings/save_json/type=acf-taxonomy', __NAMESPACE__ . '\json_save_path_for_taxonomies' );
add_filter( 'acf/json/save_file_name', __NAMESPACE__ . '\json_filename', 10, 3 );

/**
 * Set a custom ACF JSON load path.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#loading-explained
 *
 * @param array $paths Existing, incoming paths.
 *
 * @return array $paths New, outgoing paths.
 *
 * @since 0.1.1
 */
function json_load_paths( $paths ) {
	$paths[] = \MY_THEME\ROOT_PATH . '/acf-json/field-groups';
	$paths[] = \MY_THEME\ROOT_PATH . '/acf-json/options-pages';
	$paths[] = \MY_THEME\ROOT_PATH . '/acf-json/post-types';
	$paths[] = \MY_THEME\ROOT_PATH . '/acf-json/taxonomies';

	return $paths;
}

/**
 * Set custom ACF JSON save point for
 * ACF generated post types.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
 *
 * @return string $path New, outgoing path.
 *
 * @since 0.1.1
 */
function json_save_path_for_post_types() {
	return \MY_THEME\ROOT_PATH . '/acf-json/post-types';
}

/**
 * Set custom ACF JSON save point for
 * ACF generated field groups.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
 *
 * @return string $path New, outgoing path.
 *
 * @since 0.1.1
 */
function json_save_path_for_field_groups() {
	return \MY_THEME\ROOT_PATH . '/acf-json/field-groups';
}

/**
 * Set custom ACF JSON save point for
 * ACF generated taxonomies.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
 *
 * @return string $path New, outgoing path.
 *
 * @since 0.1.1
 */
function json_save_path_for_taxonomies() {
	return \MY_THEME\ROOT_PATH . '/acf-json/taxonomies';
}

/**
 * Set custom ACF JSON save point for
 * ACF generated Options Pages.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
 *
 * @return string $path New, outgoing path.
 *
 * @since 0.1.1
 */
function json_save_path_for_option_pages() {
	return \MY_THEME\ROOT_PATH . '/acf-json/options-pages';
}

/**
 * MY_THEMEize the file names for each file.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/#saving-explained
 *
 * @param string $filename  The default filename.
 * @param array  $post      The main post array for the item being saved.
 *
 * @return string $filename
 *
 * @since  0.1.1
 */
function json_filename( $filename, $post ) {
	$filename = str_replace(
		array(
			' ',
			'_',
		),
		array(
			'-',
			'-',
		),
		$post['title']
	);

	$filename = strtolower( $filename ) . '.json';

	return $filename;
}