<?php 

/**
 * Custom function with helpers for rendering infinite depth 
 * bootstrap 5 navigation menus without needing a custom walker
 * 
 * USAGE: MY_THEME\inc\render_bootstrap_menu($menu_name)
 * 
 * Customize the markup output by modifying the render_menu_item function
 *
 * @param string $menu_name The menu slug or ID to retrieve.
 */


namespace MY_THEME\inc;

// This is where the magic happens!
// This function recursively renders each item with children infinitely deep.
// Feel free to modify the markup as needed
function render_menu_item($menu_item, $children = []) {
  $has_children = !empty($children);
  $active_class = $menu_item->current ? ' active ' : '';
  $rel = !empty($menu_item->xfn) ? ' rel="' . esc_attr($menu_item->xfn) . '"' : '';

  echo '<li class="nav-item ' . $active_class . '">';

  if ($has_children) {
      // Parent item with a dropdown.
      echo '<a href="' . esc_url($menu_item->url) . '" class="nav-link dropdown-toggle ' . $active_class . '" data-bs-toggle="dropdown" role="button" aria-expanded="false">' . $menu_item->title . '</a>';
      echo '<ul class="dropdown-menu">';

      // Recursive call to render child items.
      foreach ($children as $child) {
          render_menu_item($child['item'], $child['children']);
      }

      echo '</ul>';
  } else {
      // Menu item without children.
      echo '<a href="' . esc_url($menu_item->url) . '" class="nav-link' . $active_class . '"' . $rel . '>' . $menu_item->title . '</a>';
  }

  echo '</li>';
}


// Checks a menu item against queried object to determin if it is the current one
function check_if_menu_item_is_current($menu_item){
  $current_object_id = get_queried_object_id(); // Get the ID of the current post, page, or archive item.
  if (
    (intval($menu_item->object_id) === $current_object_id) || // Match by post ID.
    (is_front_page() && $menu_item->object === 'page' && $menu_item->url === home_url('/')) || // Special handling for the front page.
    (is_home() && $menu_item->object === 'page' && get_option('page_for_posts') == $menu_item->object_id) // Special handling for the blog posts page.
  ) {
      return true;
  } else {
      return false;
  }
}

// This is typically less robust and not used, but can be swapped in if errors are occuring with the standard check
// function check_if_menu_item_is_current_by_url( $menu_item ) {
//   $actual_link = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//   if ( $actual_link == $menu_item->url ) {
//       return true;
//   }
//   return false;
// }

// This transforms the flat list that wp_get_nav_menu_items() returns into a multi-level array with nested children
function build_menu_tree(array $menu_items, $parent_id = 0) {
  $menu_tree = [];

  foreach ($menu_items as $menu_item) {

      // wp_get_nav_menu_items() does not include current attribute so we do it manually
      if(check_if_menu_item_is_current($menu_item)){
        $menu_item->current = true;
      }

      // Check if this item is a child of the current parent
      if ($menu_item->menu_item_parent == $parent_id) {
          // Recursively find children for the current menu item
          $children = build_menu_tree($menu_items, $menu_item->ID);

          // Add the menu item and its children to the tree
          $menu_tree[] = [
              'item' => $menu_item,
              'children' => $children
          ];
      }
  }

  return $menu_tree;
}


/**
 * Custom function to render a WordPress menu with infinite levels of nesting
 * using Bootstrap 5 markup.
 *
 * @param string $menu_name The menu slug or ID to retrieve.
 */
function render_bootstrap_menu($menu_name, $id=false) {
    // Get the menu object by name (slug or ID).
    $menu = wp_get_nav_menu_object($menu_name);

    if ($menu) {
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_tree = build_menu_tree($menu_items);

        // Print the menu tree
        // echo '<pre>' . print_r($menu_tree, true) . '</pre>';

        // Output the menu with Bootstrap 5 markup.
        if($id){
            echo '<ul id="' . $id . '" class="navbar-nav ms-auto mb-2 mb-md-0">'; // Bootstrap nav wrapper.
        } else {
            echo '<ul class="navbar-nav ms-auto mb-2 mb-md-0">'; // Bootstrap nav wrapper.
        }
        foreach ($menu_tree as $menu) {
            render_menu_item($menu['item'], $menu['children']); // Render each item.
        }
        echo '</ul>'; // End Bootstrap nav wrapper.
        
    } else {
        return;
    }
}
?>
