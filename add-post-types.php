<?php 

namespace MY_THEME\inc\functions;

// Register Poem MY_THEME Post Type
function create_poem_post_type() {
  register_post_type('poem', [
      'labels' => [
          'name'               => 'Poems',
          'singular_name'      => 'Poem',
          'add_new_item'       => 'Add New Poem',
          'edit_item'          => 'Edit Poem',
          'new_item'           => 'New Poem',
          'view_item'          => 'View Poem',
          'search_items'       => 'Search Poems',
          'not_found'          => 'No poems found',
          'not_found_in_trash' => 'No poems found in Trash',
      ],
      'menu_icon' => 'dashicons-book',
      'public'             => true,
      'has_archive'        => true,
      'rewrite'            => [
          'slug' => 'poems',
          'with_front' => false, // Optional: remove the blog prefix if your permalinks have it
      ],
      'supports'           => ['title', 'editor', 'excerpt'],
      'show_in_rest'       => true,
      'template' => [
          ['core/verse', [
              'placeholder' => 'Paste your poem here...',
          ]],
      ],
      'template_lock'      => 'all', // Set to 'all' if you want to lock the template
  ]);
}
add_action('init', __NAMESPACE__ . '\create_poem_post_type');

// Register Theme Taxonomy (Tag-style)
function register_theme_taxonomy() {
  register_taxonomy('theme', 'poem', [
      'labels' => [
          'name'          => 'Themes',
          'singular_name' => 'Theme',
          'search_items'  => 'Search Themes',
          'all_items'     => 'All Themes',
          'edit_item'     => 'Edit Theme',
          'update_item'   => 'Update Theme',
          'add_new_item'  => 'Add New Theme',
          'new_item_name' => 'New Theme Name',
      ],
      'hierarchical' => false, // Tag-style
      'show_in_rest' => true,
      'rewrite'      => ['slug' => 'themes'],
  ]);
}
add_action('init', __NAMESPACE__ . '\register_theme_taxonomy');

// Register Workshop Taxonomy (Category-style)
function register_workshop_taxonomy() {
  register_taxonomy('workshop', 'poem', [
      'labels' => [
          'name'          => 'Workshops',
          'singular_name' => 'Workshop',
          'search_items'  => 'Search Workshops',
          'all_items'     => 'All Workshops',
          'parent_item'   => 'Parent Workshop',
          'edit_item'     => 'Edit Workshop',
          'update_item'   => 'Update Workshop',
          'add_new_item'  => 'Add New Workshop',
          'new_item_name' => 'New Workshop Name',
      ],
      'hierarchical' => true, // Category-style
      'show_in_rest' => true,
      'rewrite'      => ['slug' => 'workshops'],
  ]);
}
add_action('init', __NAMESPACE__ . '\register_workshop_taxonomy');

// Register Audience Taxonomy (Category-style)
function register_audience_taxonomy() {
  register_taxonomy('audience', 'poem', [
      'labels' => [
          'name'          => 'Audiences',
          'singular_name' => 'Audience',
          'search_items'  => 'Search Audiences',
          'all_items'     => 'All Audiences',
          'parent_item'   => 'Parent Audience',
          'edit_item'     => 'Edit Audience',
          'update_item'   => 'Update Audience',
          'add_new_item'  => 'Add New Audience',
          'new_item_name' => 'New Audience Name',
      ],
      'hierarchical' => true, // Category-style
      'show_in_rest' => true,
      'rewrite'      => ['slug' => 'audiences'],
  ]);
}
add_action('init', __NAMESPACE__ . '\register_audience_taxonomy');





?>