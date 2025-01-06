<?php
/*
* RECIPES FOR MODIFYING POST TYPES
*/

// ARCHIVE ONLY
// Useful for team pages if team members do not have individual pages

// redirect single to the archive page, scrolled to current ID 
add_action( 'template_redirect', function() {
	if ( is_singular('{post-type}') ) {
			global $post;
			$redirect_link = home_url();
			wp_safe_redirect( $redirect_link, 302 );
			exit;
	}
});

// turn off pagination for the archive page
add_action('parse_query', function($query) {
	if (is_post_type_archive('{post-type}')) {
			$query->set('nopaging', 1);
	}
});