<?php

namespace MY_THEME\inc\functions;

/**
 * Gating the site behind login and protecting all endpoints like REST API and feeds.
 */

// Redirect all non-logged-in users to the login page.
add_action('template_redirect', function () {

  // User check
  if (
    is_user_logged_in() &&
    current_user_can('custom_permission')
  ) return;

  // Whitelisted Pages
  if (
    is_page('wp-login.php') ||
    is_admin() ||
    is_front_page()
  ) return;

  // Otherwise redirect to login
  wp_redirect(wp_login_url());
  exit;
});

// Disable REST API for non-logged-in users.
add_filter('rest_authentication_errors', function ($result) {
  if (! is_user_logged_in()) {
    return new \WP_Error('rest_forbidden', __('You must be logged in to access the REST API.', 'textdomain'), array('status' => 401));
  }
  return $result;
});

// Protect RSS feeds for non-logged-in users.
add_action('do_feed', function () {
  if (! is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
  }
}, 1);

// Optional: Disable XML-RPC access for non-logged-in users.
add_filter('xmlrpc_enabled', function ($enabled) {
  if (! is_user_logged_in()) {
    return false;
  }
  return $enabled;
});
