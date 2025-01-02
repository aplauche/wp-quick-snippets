<?php  

namespace MY_THEME\inc\functions;

function handle_favorite_post() {
  check_ajax_referer('favorite_nonce', 'nonce');

  $post_id = intval($_POST['post_id']);
  $user_id = get_current_user_id();
  
  if (!$user_id || !$post_id) {
      wp_send_json_error('Invalid request');
  }

  $favorites = get_user_meta($user_id, 'favorite_posts', true) ?: [];

  if (in_array($post_id, $favorites)) {
      // Unfavorite
      $favorites = array_diff($favorites, [$post_id]);
  } else {
      // Favorite
      $favorites[] = $post_id;
  }

  update_user_meta($user_id, 'favorite_posts', $favorites);

  wp_send_json_success(['favorites' => $favorites]);
  
}
add_action('wp_ajax_toggle_favorite', __NAMESPACE__ . '\handle_favorite_post');


function display_user_favorites() {
    if (!is_user_logged_in()) {
        return '<p>You must be logged in to view your favorites.</p>';
    }

    $user_id = get_current_user_id();
    $favorites = get_user_meta($user_id, 'favorite_posts', true) ?: [];

    if (empty($favorites)) {
        return '<p>You have no favorite posts yet.</p>';
    }

    $output = '<ul class="favorite-posts">';
    foreach ($favorites as $post_id) {
        $output .= '<li>' . get_the_title($post_id) . '</li>';
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('user_favorites', __NAMESPACE__ . '\display_user_favorites');