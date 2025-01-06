<?php


// Add custom columns for the "poem" post type
add_filter('manage_edit-poem_columns', function ($columns) {
    // Remove the default 'Date' column
    $date = $columns['date'];
    unset($columns['date']);

    // Add custom taxonomy columns
    $columns['theme'] = __('Theme', 'textdomain');
    $columns['workshop'] = __('Workshop', 'textdomain');
    $columns['audience'] = __('Audience', 'textdomain');

    // Re-add the 'Date' column at the end
    $columns['date'] = $date;

    return $columns;
});

// Populate the custom columns
add_action('manage_poem_posts_custom_column', function ($column, $post_id) {
    // Check if the column is one of the custom taxonomy columns
    if (in_array($column, ['theme', 'workshop', 'audience'])) {
        // Get terms associated with the post for the specific taxonomy
        // This works since our column names exactly match the taxonomies
        $terms = get_the_terms($post_id, $column);
        if (!empty($terms) && !is_wp_error($terms)) {
            // Display terms as a comma-separated list
            $term_names = wp_list_pluck($terms, 'name');
            echo implode(', ', $term_names);
        } else {
            echo __('--', 'textdomain');
        }
    }
}, 10, 2);



// The following two functions allow sorting and can be disabled if not needed

// Make columns sortable (optional)
add_filter('manage_edit-poem_sortable_columns', function ($columns) {
    // create the orderby options
    $columns['theme'] = 'theme';
    $columns['workshop'] = 'workshop';
    $columns['audience'] = 'audience';
    return $columns;
});

// Sort posts by custom taxonomy terms in the dashboard (optional)
add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');
    // if orderby is one of the custom taxonomy columns modify the query as needed
    if (in_array($orderby, ['theme', 'workshop', 'audience'])) {
        $query->set('orderby', 'taxonomy');
        $query->set('taxonomy', $orderby);
    }
});
