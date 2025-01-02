<?php 

namespace MY_THEME\inc;

// Add the facilitator role for users of the site
function add_roles() {
  add_role('facilitator', 'Facilitator', [
      'read' => true, // Can read private content
      'favorite_post' => true, 
  ]);
}
add_action('init', __NAMESPACE__ . '\add_roles');


// Redirect facilitators away from the dashboard.
add_action( 'admin_init', function() {
  if ( current_user_can( 'facilitator' ) && ! defined( 'DOING_AJAX' ) ) {
      wp_redirect( home_url() );
      exit;
  }
} );

// Hide the admin bar for facilitators.
add_action( 'after_setup_theme', function() {
  if ( current_user_can( 'facilitator' ) ) {
      show_admin_bar( false );
  }
} );

// Add a capability for "facilitate_workshops" used to gate site
function add_facilitate_workshops_capability() {
  $roles = get_editable_roles();

  foreach ( $roles as $role_name => $role_info ) {
      if ( $role_name !== 'subscriber' ) {
          $role = get_role( $role_name );
          if ( $role ) {
              $role->add_cap( 'facilitate_workshops' );
          }
      }
  }
}
add_action( 'admin_init', __NAMESPACE__ . '\add_facilitate_workshops_capability' );
