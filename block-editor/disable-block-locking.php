<?php

namespace MY_THEME\inc\functions;

add_filter('block_editor_settings_all', function ($settings, $context) {
  if (
    // isset( $context->post ) && 
    // 'post' === $context->post->post_type && 
    ! current_user_can('edit_theme_options')
  ) {
    $settings['canLockBlocks'] = false; // Disable locking/unlocking for non-administrators editing posts.
    $settings['codeEditingEnabled'] = false; // Disable access to the Code Editor.    
  }

  return $settings;
}, 10, 2);
