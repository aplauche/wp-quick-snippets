<?php 

namespace MY_THEME\inc\setup;


// Disable Comments on the Frontend
function disable_comments_on_frontend() {
  // Remove the comments template
  add_filter('comments_template', '__return_null');
  // Disable support for comments on all post types
  foreach (get_post_types() as $post_type) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
  }
}
add_action('init', __NAMESPACE__ . '\disable_comments_on_frontend');

// Remove Comment Menu from Admin
function disable_comments_admin_menu() {
  remove_menu_page('edit-comments.php'); // Removes the Comments menu item
}
add_action('admin_menu', __NAMESPACE__ . '\disable_comments_admin_menu');

// Redirect Any Direct Access to Comments Admin Pages
function disable_comments_admin_redirect() {
  global $pagenow;
  if ($pagenow === 'edit-comments.php' || strpos($_SERVER['REQUEST_URI'], 'comment') !== false) {
      wp_redirect(admin_url());
      exit;
  }
}
add_action('admin_init', __NAMESPACE__ . '\disable_comments_admin_redirect');

// Remove Comments from the Admin Bar
function disable_comments_admin_bar() {
  if (is_admin_bar_showing()) {
      remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
}
add_action('init', __NAMESPACE__ . '\disable_comments_admin_bar');

?>