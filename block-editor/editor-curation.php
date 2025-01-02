<?php 


namespace MY_THEME\inc;

/**
 * Disable controls for specific blocks when editing posts.
 *
 * @param array  $args       The original block type arguments.
 * @param string $block_type The name of the block type being registered.
 * @return array             Modified block type arguments.
 */
function remove_block_supports_from_core_blocks( $args, $block_type ) {
  // Get the current post type using a utility function.
  $post_type = parse_post_type();

  // Check if we are editing a post.
  if ( 'post' === $post_type ) {
      // List of block types to modify.
      $block_types_to_modify = [
        'core/verse'
      ];
    
      // Check if the current block type is in the list.
      if ( in_array( $block_type, $block_types_to_modify, true ) ) {
          // Disable color controls.
          // die(print_r($args['supports']));
          $args['supports']['color'] = array(
              'text'       => false,
              'background' => false,
              'link'       => false,
          );
          $args['supports']['background'] = false;
          $args['supports']['typography'] = false;
          $args['supports']['spacing'] = false;
          $args['supports']['dimensions'] = false;
          $args['supports']['__experimentalBorder'] = false;
      }

  }



  return $args;
}
add_filter( 'register_block_type_args', __NAMESPACE__ . '\remove_block_supports_from_core_blocks', 10, 2 );

/**
* Retrieve the current post type from query parameters.
*
* @return string The post type if found, otherwise an empty string.
*/
function parse_post_type() {
$post_type = '';

if ( isset( $_GET['post'] ) ) {
  $post_type = get_post_type( absint( $_GET['post'] ) );
} elseif ( isset( $_GET['post_type'] ) ) {
  $post_type = sanitize_key( $_GET['post_type'] );
} elseif ( isset( $_GET['postType'] ) ) {
  $post_type = sanitize_key( $_GET['postType'] );
}

return $post_type;
}