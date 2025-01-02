<?php 

namespace MY_THEME\inc\functions;


// Remove All Default Dashboard Widgets, Including "Welcome to WordPress"
function clean_dashboard_widgets() {
  global $wp_meta_boxes;

  // Remove default dashboard widgets
  $wp_meta_boxes['dashboard'] = [];

  // Remove the "Welcome" panel
  remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', __NAMESPACE__ . '\clean_dashboard_widgets', 900);

// Add Custom Dashboard Widget
function add_custom_dashboard_widget() {
  wp_add_dashboard_widget(
      'custom_dashboard_widget', // Widget ID
      'Welcome to Your Dashboard', // Widget Title
      __NAMESPACE__ . '\custom_dashboard_widget_content' // Callback function for content
  );
}
add_action('wp_dashboard_setup', __NAMESPACE__ . '\add_custom_dashboard_widget', 999);

// Custom Dashboard Widget Content
function custom_dashboard_widget_content() {
  ?>
    <h2>Manage Resources</h2>
    <p>Use the quicklinks below to add and manage content in your database.</p>
    <ul class="custom-db__buttons">
      <li><a class="custom-db__button" href="<?php echo admin_url('post-new.php?post_type=example') ?>">Add a New Entry</a></li>
      <li><a class="custom-db__button" href="<?php echo admin_url('edit.php?post_type=example') ?>">Manage Entries</a></li>
    </ul>
  <?php
}

// Add Custom CSS to Make the Widget Span Two Columns
function custom_dashboard_widget_css() {
  echo '
  <style>
      #postbox-container-1 {
          grid-column: span 2 !important; /* For WordPress 5.5+ with grid layout */
          width: 100% !important; /* Fallback for older versions */
      }
      .custom-db__buttons {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
      }

      .custom-db__button {
        display: inline-block;
        color: black;
        background: white;
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        border: 2px solid black;
        transition: all 0.2s ease;
      }

      .custom-db__button:hover, .custom-db__button:focus-visible {
        background: black;
        color: white;
      }
  </style>';
}
add_action('admin_head', __NAMESPACE__ . '\custom_dashboard_widget_css');


// Ensure Custom Widget Displays by Default
function force_custom_dashboard_widget_display($user_id) {
  // Set the custom dashboard widget to display at the top
  update_user_meta($user_id, 'meta-box-order_dashboard', [
      'normal' => 'custom_dashboard_widget',
      'side'   => '',
  ]);
  // Clear screen layout preferences to ensure the widget is visible
  update_user_meta($user_id, 'screen_layout_dashboard', 1);
}
add_action('user_register', __NAMESPACE__ . '\force_custom_dashboard_widget_display');

// Apply to All Existing Users (Optional)
function apply_widget_to_existing_users() {
  $users = get_users(['fields' => ['ID']]);
  foreach ($users as $user) {
      force_custom_dashboard_widget_display($user->ID);
  }
}
add_action('init', __NAMESPACE__ . '\apply_widget_to_existing_users');

?>