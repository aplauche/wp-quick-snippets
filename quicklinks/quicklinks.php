<?php

// Define the meta key for storing quicklinks
define( 'QUICKLINKS_META_KEY', 'user_quicklinks' );

/**
 * Define the default quicklinks.
 *
 * @return array Array of default quicklink objects.
 */
function user_quicklinks_get_default_links() {
	return array(
	);
}


/**
 * Ensure the user meta exists for the logging-in user.
 *
 * @param string   $user_login The user's login username.
 * @param WP_User $user       The logged-in user object.
 */
function user_quicklinks_ensure_meta( $user_login, $user ) {
	if ( ! get_user_meta( $user->ID, QUICKLINKS_META_KEY ) ) {
    $default_links = user_quicklinks_get_default_links();
		update_user_meta( $user->ID, QUICKLINKS_META_KEY, wp_json_encode( $default_links ) );
	}
}
add_action( 'wp_login', 'user_quicklinks_ensure_meta', 10, 2 );

/**
 * Ensure the user meta exists when a new user is created.
 *
 * @param int $user_id The ID of the newly created user.
 */
function user_quicklinks_create_user( $user_id ) {
	if ( ! get_user_meta( $user_id, QUICKLINKS_META_KEY ) ) {
    $default_links = user_quicklinks_get_default_links();
		update_user_meta( $user_id, QUICKLINKS_META_KEY, wp_json_encode( $default_links ) );
	}
}
add_action( 'user_register', 'user_quicklinks_create_user' );


function pd_render_quicklink($index, $quicklink){
  $title = sanitize_text_field( $quicklink['title'] );
  $url   = esc_url_raw( $quicklink['url'] );

  ob_start(); ?>
  <li data-index="<?php echo esc_attr( $index ) ?>">
    <div class="handle"><svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M5.5 6C5.77614 6 6 5.77614 6 5.5C6 5.22386 5.77614 5 5.5 5C5.22386 5 5 5.22386 5 5.5C5 5.77614 5.22386 6 5.5 6Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M5.5 12.5C5.77614 12.5 6 12.2761 6 12C6 11.7239 5.77614 11.5 5.5 11.5C5.22386 11.5 5 11.7239 5 12C5 12.2761 5.22386 12.5 5.5 12.5Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M5.5 19C5.77614 19 6 18.7761 6 18.5C6 18.2239 5.77614 18 5.5 18C5.22386 18 5 18.2239 5 18.5C5 18.7761 5.22386 19 5.5 19Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 6C12.2761 6 12.5 5.77614 12.5 5.5C12.5 5.22386 12.2761 5 12 5C11.7239 5 11.5 5.22386 11.5 5.5C11.5 5.77614 11.7239 6 12 6Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 12.5C12.2761 12.5 12.5 12.2761 12.5 12C12.5 11.7239 12.2761 11.5 12 11.5C11.7239 11.5 11.5 11.7239 11.5 12C11.5 12.2761 11.7239 12.5 12 12.5Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M12 19C12.2761 19 12.5 18.7761 12.5 18.5C12.5 18.2239 12.2761 18 12 18C11.7239 18 11.5 18.2239 11.5 18.5C11.5 18.7761 11.7239 19 12 19Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.5 6C18.7761 6 19 5.77614 19 5.5C19 5.22386 18.7761 5 18.5 5C18.2239 5 18 5.22386 18 5.5C18 5.77614 18.2239 6 18.5 6Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.5 12.5C18.7761 12.5 19 12.2761 19 12C19 11.7239 18.7761 11.5 18.5 11.5C18.2239 11.5 18 11.7239 18 12C18 12.2761 18.2239 12.5 18.5 12.5Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M18.5 19C18.7761 19 19 18.7761 19 18.5C19 18.2239 18.7761 18 18.5 18C18.2239 18 18 18.2239 18 18.5C18 18.7761 18.2239 19 18.5 19Z" fill="#000000" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
    <a href="<?php echo $url ?>" target="_blank"><?php echo esc_html( $title ) ?> <svg width="16px" height="16px" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="inherit"><path d="M9.17137 14.8284L14.8282 9.17152M14.8282 9.17152H9.87848M14.8282 9.17152V14.1213"  stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M21 3.6V20.4C21 20.7314 20.7314 21 20.4 21H3.6C3.26863 21 3 20.7314 3 20.4V3.6C3 3.26863 3.26863 3 3.6 3H20.4C20.7314 3 21 3.26863 21 3.6Z"  stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a>
    <button type="button" class="delete-quicklink" data-index="<?php echo esc_attr( $index ) ?>">
      <svg width="16px" height="16px" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="#000000"><path d="M6.75827 17.2426L12.0009 12M17.2435 6.75736L12.0009 12M12.0009 12L6.75827 6.75736M12.0009 12L17.2435 17.2426" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
    </button>
  </li>
  <?php return ob_get_clean();
}


/**
 * Shortcode to display the quicklinks list.
 *
 * @return string HTML output for the quicklinks.
 */
function user_quicklinks_shortcode() {
  if ( ! is_user_logged_in() ) {
		return '<p>You must be logged in to manage your quicklinks.</p>';
	}

  $current_user_id = get_current_user_id();
	$quicklinks_json = get_user_meta( $current_user_id, QUICKLINKS_META_KEY, true );
	$quicklinks = json_decode( $quicklinks_json, true );

  ob_start();
	?>

    <ul id="user-quicklinks-list">
      <?php
      if ( ! empty( $quicklinks ) && is_array( $quicklinks ) ) {
        foreach ( $quicklinks as $index => $quicklink ) {
          if ( isset( $quicklink['title'] ) && isset( $quicklink['url'] ) ) {
            echo pd_render_quicklink($index, $quicklink);
          }
        }
      } else {
        echo '<li>No quicklinks added yet.</li>';
      }
      ?>
    </ul>
  <?php
	return ob_get_clean();
}
add_shortcode( 'user_quicklinks', 'user_quicklinks_shortcode' );


/**
 * Shortcode to display the quicklinks form.
 *
 * @return string HTML output for the form.
 */
function user_quicklinks_form_shortcode() {
	if ( ! is_user_logged_in() ) {
		return '<p>' . esc_html__( 'You must be logged in to manage your quicklinks.', 'user-quicklinks' ) . '</p>';
	}

	$current_user_id = get_current_user_id();
	$quicklinks_json = get_user_meta( $current_user_id, QUICKLINKS_META_KEY, true );
	$quicklinks = json_decode( $quicklinks_json, true );

	ob_start();
	?>
	<form id="add-quicklink-form" class="row g-3 align-items-end justify-content-end">
    <div class="col-auto">
      <label for="quicklink-title" class="form-label">Title</label>
      <input type="text" class="form-control" id="quicklink-title" required>
    </div>
    <div class="col-auto">
      <label for="quicklink-url" class="form-label">URL</label>
      <input type="url" class="form-control" id="quicklink-url" required>
    </div>
    <div class="col-auto">
      <button id="add-quicklink-button" type="button" class="btn btn-primary">Add Quicklink</button>
    </div>
	</form>

	<?php
	return ob_get_clean();
}
add_shortcode( 'user_quicklinks_form', 'user_quicklinks_form_shortcode' );


/**
 * Enqueue frontend scripts.
 */
function user_quicklinks_enqueue_scripts() {
	if ( is_user_logged_in() ) {
		wp_enqueue_script( 'user-quicklinks-script', get_stylesheet_directory_uri() . '/src/js/modules/quicklinks.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.1', true );
		wp_localize_script(
			'user-quicklinks-script',
			'userQuicklinksAjax',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'add_nonce'   => wp_create_nonce( 'add_quicklink_action' ),
				'delete_nonce' => wp_create_nonce( 'delete_quicklink_action' ),
        'reorder_nonce' => wp_create_nonce( 'reorder_user_quicklinks_action' ), 
				'i18n'      => array(
					'delete_confirm' => esc_html__( 'Are you sure you want to delete this quicklink?', 'user-quicklinks' ),
					'generic_error'  => esc_html__( 'An error occurred. Please try again.', 'user-quicklinks' ),
				),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'user_quicklinks_enqueue_scripts' );



/**
 * AJAX handler for adding a user quicklink.
 */
function user_quicklinks_add_quicklink_callback() {
	// Security check
	check_ajax_referer( 'add_quicklink_action', '_ajax_nonce' );


	if ( ! is_user_logged_in() ) {
		wp_send_json_error( esc_html__( 'You must be logged in.', 'user-quicklinks' ) );
		wp_die();
	}

	if ( ! isset( $_POST['title'] ) || ! isset( $_POST['url'] ) ) {
		wp_send_json_error( esc_html__( 'Missing title or URL.', 'user-quicklinks' ) );
		wp_die();
	}

	$title = sanitize_text_field( $_POST['title'] );
	$url   = esc_url_raw( $_POST['url'] );
	$user_id = get_current_user_id();
	$quicklinks_json = get_user_meta( $user_id, QUICKLINKS_META_KEY, true );
	$quicklinks = json_decode( $quicklinks_json, true );

	if ( ! is_array( $quicklinks ) ) {
		$quicklinks = [];
	}

	$quicklinks[] = array( 'title' => $title, 'url' => $url );

	update_user_meta( $user_id, QUICKLINKS_META_KEY, wp_json_encode( $quicklinks ) );

	// Send back the updated quicklinks list HTML
	ob_start();
	if ( ! empty( $quicklinks ) ) {
		foreach ( $quicklinks as $index => $quicklink ) {
			if ( isset( $quicklink['title'] ) && isset( $quicklink['url'] ) ) {
				echo pd_render_quicklink($index, $quicklink);
			}
		}
	} else {
		echo '<li>No quicklinks added yet.</li>';
	}
	$output = ob_get_clean();

	wp_send_json_success( $output );
	wp_die();
}
add_action( 'wp_ajax_add_user_quicklink', 'user_quicklinks_add_quicklink_callback' );


/**
 * AJAX handler for deleting a user quicklink.
 */
function user_quicklinks_delete_quicklink_callback() {
	// Security check
	check_ajax_referer( 'delete_quicklink_action', '_ajax_nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( esc_html__( 'You must be logged in.', 'user-quicklinks' ) );
		wp_die();
	}

	if ( ! isset( $_POST['index'] ) ) {
		wp_send_json_error( esc_html__( 'Missing index.', 'user-quicklinks' ) );
		wp_die();
	}

	$index = absint( $_POST['index'] ); // Sanitize the index as an integer
	$current_user_id = get_current_user_id();
	$quicklinks_json = get_user_meta( $current_user_id, QUICKLINKS_META_KEY, true );
	$quicklinks = json_decode( $quicklinks_json, true );

	if ( is_array( $quicklinks ) && isset( $quicklinks[ $index ] ) ) {
		unset( $quicklinks[ $index ] );
		// Re-index the array to avoid potential issues with JSON encoding
		$quicklinks = array_values( $quicklinks );
		update_user_meta( $current_user_id, QUICKLINKS_META_KEY, wp_json_encode( $quicklinks ) );

		// Send back the updated quicklinks list HTML
		ob_start();
		if ( ! empty( $quicklinks ) ) {
			foreach ( $quicklinks as $new_index => $quicklink ) {
				if ( isset( $quicklink['title'] ) && isset( $quicklink['url'] ) ) {
					echo pd_render_quicklink($index, $quicklink);
				}
			}
		} else {
			echo '<li>No quicklinks added yet.</li>';
		}
		$output = ob_get_clean();
		wp_send_json_success( $output );
	} else {
		wp_send_json_error( esc_html__( 'Quicklink not found.', 'user-quicklinks' ) );
	}

	wp_die();
}
add_action( 'wp_ajax_delete_user_quicklink', 'user_quicklinks_delete_quicklink_callback' );


/**
 * AJAX handler for reordering user quicklinks.
 */
function user_quicklinks_reorder_callback() {
	// Security check
	check_ajax_referer( 'reorder_user_quicklinks_action', '_ajax_nonce' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( esc_html__( 'You must be logged in.', 'user-quicklinks' ) );
		wp_die();
	}

	if ( ! isset( $_POST['order'] ) || ! is_array( $_POST['order'] ) ) {
		wp_send_json_error( esc_html__( 'Invalid order data.', 'user-quicklinks' ) );
		wp_die();
	}

	$new_order_indices = wp_unslash( $_POST['order'] ); // Array of string indices (original positions)
	$current_user_id   = get_current_user_id();
	$quicklinks_json   = get_user_meta( $current_user_id, QUICKLINKS_META_KEY, true );
	$current_quicklinks  = json_decode( $quicklinks_json, true );
	$reordered_quicklinks = [];

	if ( is_array( $current_quicklinks ) ) {
		// Create a temporary associative array to easily access quicklinks by their original index
		$indexed_quicklinks = [];
		foreach ( $current_quicklinks as $index => $quicklink ) {
			$indexed_quicklinks[ $index ] = $quicklink;
		}

		// Reconstruct the ordered array based on the new order of original indices
		foreach ( $new_order_indices as $original_index_str ) {
			$original_index = absint( $original_index_str );
			if ( isset( $indexed_quicklinks[ $original_index ] ) ) {
				$reordered_quicklinks[] = $indexed_quicklinks[ $original_index ];
			}
		}

		update_user_meta( $current_user_id, QUICKLINKS_META_KEY, wp_json_encode( $reordered_quicklinks ) );
		wp_send_json_success();
	} else {
		wp_send_json_error( esc_html__( 'Error reordering quicklinks.', 'user-quicklinks' ) );
	}

	wp_die();
}
add_action( 'wp_ajax_reorder_user_quicklinks', 'user_quicklinks_reorder_callback' );





/**
 * Function to wipe the user_quicklinks metadata from all users.
 *
 * WARNING: This will remove all existing quicklinks for all users.
 * Use with extreme caution and ensure you have a database backup.
 */
function user_quicklinks_wipe_all_user_metadata() {
	$users = get_users();
	$meta_key = QUICKLINKS_META_KEY;
	$default_links = user_quicklinks_get_default_links();
	$default_links_json = wp_json_encode( $default_links );

	foreach ( $users as $user ) {
		update_user_meta( $user->ID, $meta_key, $default_links_json );
		// Optional: You can add a log message here if needed
		// error_log( 'Wiped quicklinks for user ID: ' . $user->ID );
	}

	// Optional: Display a success message to the admin
	add_action( 'admin_notices', function() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Successfully wiped and reset default quicklinks for all users.', 'user-quicklinks' ); ?></p>
		</div>
		<?php
	} );
}

/**
 * Example of how to trigger the wipe function.
 *
 * IMPORTANT: Uncommenting this will make the wipe function run on every admin page load.
 * Make sure to uncomment, visit an admin page ONCE, and then comment it out again!
 */
// add_action( 'admin_init', 'user_quicklinks_wipe_all_user_metadata' );
