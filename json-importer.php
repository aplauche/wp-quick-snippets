<?php
/**
 * Plugin Name:       WP Basic JSON Importer
 * Description:       A plugin for importing json feeds into posts.
 * Version:           0.1.0
 * Author:            Anton Plauche
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpbji
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));
define('PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));

class WPBJI_JSON_Importer {

  private $feed_url;
  private $save_json_file;

  function __construct(){
    $this->save_json_file = false;
    $this->feed_url = "https://jsonplaceholder.typicode.com/posts";

    add_filter('cron_schedules', [$this, 'add_custom_cron_schedule']);
    add_action('wp', [$this, 'schedule_event']);
    add_action('wpbji_fetch_json_feed_event', [$this, 'fetch_feed']);

    add_action('admin_menu', [$this, 'add_json_fetcher_menu']);

  }

  function add_custom_cron_schedule($schedules){
    // Add a new cron schedule for 24 hours
    $schedules['every_24_hours'] = array(
      'interval' => 86400, // 24 hours in seconds
      'display' => __('Every 24 Hours')
    );
    return $schedules;
  }


  function schedule_event(){
    if (!wp_next_scheduled('wpbji_fetch_json_feed_event')) {
      wp_schedule_event(time(), 'every_24_hours', 'wpbji_fetch_json_feed_event');
    }
  }

  function fetch_feed(){

    // Fetch the feed
    $response = wp_remote_get($this->feed_url);
  
    // Check for errors in the response
    if (is_wp_error($response)) {
        error_log('Failed to fetch RSS feed: ' . $response->get_error_message());
        return;
    }
  
    // Get the body of the response
    $body = wp_remote_retrieve_body($response);
  
    // Decode JSON (optional if you want to process it)
    $json_data = json_decode($body, true);
  
    // Check for valid JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Invalid JSON in RSS feed');
        return;
    }

    return $this->create_posts($json_data);
  }

  function create_posts($json){
    foreach ($json as $item) {

      // raw date example
      $isoDate = "2022-09-27 18:00:00.000";
  
      // Convert UTC date to Eastern Time
      $utc_date = new DateTime($isoDate, new DateTimeZone('UTC'));
      $utc_date->setTimezone(new DateTimeZone('America/New_York')); 
  
      // Format for wordpress published date 'Y-m-d H:i:s'
      $published_date_formatted = $utc_date->format('Y-m-d H:i:s'); 
  
      // Customize data from json structure as needed
      $title = $item['title'];
      $slug = sanitize_title($title); // slugifies
      $custom_id = $item['id'];
      $published_date = $published_date_formatted; // Ensure this is in 'Y-m-d H:i:s' format
  
      // Check if a post with the same slug already exists
      $existing_post = get_posts([
          'name' => $slug,
          'post_type' => 'post',
          'post_status' => ['publish', 'draft', 'future', 'pending', 'trash', 'private', 'auto-draft', 'inherit'],
          'numberposts' => 1
      ]);
      
      // If no post, continue with import
      if (empty($existing_post)) {
          // Create a new "press" post
          $post_id = wp_insert_post([
              'post_title'   => $title,
              'post_name'    => $slug,
              'post_type'    => 'post',
              'post_status'  => 'publish',
              'post_date'    => $published_date,
              // meta fields
              'meta_input'   => [
                  'custom_id' => $custom_id
              ]
          ]);
          
          if (is_wp_error($post_id)) {
              error_log("Error creating post: " . $post_id->get_error_message());
              return;
          }
  
          // Optionally add a custom term by slug
          // $category_slug = 'press-release';
          // $taxonomy = 'format';
  
          // // Retrieve the term ID by slug in the "format" taxonomy
          // $category_term = get_term_by('slug', $category_slug, $taxonomy);
  
          // if ($category_term && !is_wp_error($category_term)) {
          //     // Assign the term to the post after inserting it
          //     wp_set_post_terms($post_id, [$category_term->term_id], $taxonomy);
          // }
  
      }
    }

    return 'success';
  }

  function add_json_fetcher_menu(){
    add_menu_page(
      'JSON Fetcher',
      'JSON Fetcher',
      'manage_options',
      'wpbji-json-fetcher',
      [$this, 'render_json_fetcher_page']
   );
  }

  function render_json_fetcher_page(){
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1>Fetch JSON Posts</h1>
        <form method="post">
            <?php
            wp_nonce_field('run_custom_cron_job', 'custom_cron_nonce');
            submit_button('Run Automation Now');
            ?>
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['custom_cron_nonce']) || !wp_verify_nonce($_POST['custom_cron_nonce'], 'run_custom_cron_job')) {
            wp_die(__('Invalid request.'));
        }
        $status = $this->fetch_feed();
        // wp_schedule_single_event(time(), 'fetch_rss_feed_event');
        if($status === 'success'){
          echo '<div class="notice notice-success is-dismissible"><p>Posts successfully fetched and created!</p></div>';
        } else {
          echo '<div class="notice notice-warning is-dismissible"><p>Something went wrong! Check the error logs for more information.</p></div>';
        }
    }
  }
}

// Entry Point
new WPBJI_JSON_Importer();