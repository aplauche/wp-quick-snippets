<?php

namespace MY_THEME\inc\functions;

// Restrict blocks for post type
function restrict_blocks_by_post_type($allowed_blocks, $editor_context) {
  // Ensure this applies only to the specific post type
  if (!empty($editor_context->post) && $editor_context->post->post_type === 'example') {
      // Specify allowed blocks
      return [
          'core/paragraph',
          'core/heading',
          'core/verse',
          'core/list',
      ];
  }

  // Return default blocks for other post types
  return $allowed_blocks;
}
add_filter('allowed_block_types_all', __NAMESPACE__ . '\restrict_blocks_by_post_type', 10, 2);