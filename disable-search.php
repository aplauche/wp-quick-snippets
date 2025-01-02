<?php 

namespace MY_THEME\inc;

// Redirects all ?s= urls to the home page instead
add_action('template_redirect', function () {
  if (is_search()) {
      wp_redirect(home_url());
      exit;
  }
});