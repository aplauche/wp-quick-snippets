<?php


/**
 * Allow markdown style formatting in ACF text fields
 */
 function custom_acf_markdown_filters($value, $post_id, $field) {
  // Check if the field type is 'text'
  if ($field['type'] === 'text' && is_string($value)) {
      // IMPORTANT - ORDER MATTERS HERE
      // add additional patterns and outputs as needed
      $value = preg_replace('/\*\*\*(.*?)\*\*\*/', '<strong>$1</strong>', $value);
      $value = preg_replace('/\*\*(.*?)\*\*/', '<em>$1</em>', $value);
  }

  return $value;
}

add_filter('acf/format_value', 'custom_acf_markdown_filters', 10, 3);
