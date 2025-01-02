<?php

namespace MY_THEME\inc;

function filter_posts_ajax()
{

    $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 25,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ];

    $taxonomies = ['theme', 'workshop', 'audience'];

    foreach ($taxonomies as $taxonomy) {
        if (!empty($_GET[$taxonomy])) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => explode(',', $_GET[$taxonomy]),
            ];
        }
    }


    if (!empty($_GET['s'])) {
        $args['s'] = sanitize_text_field($_GET['s']);
    }

    $query = new \WP_Query($args);
    $results = [];

    // this is just if you want to show a heart icon if the post is favorited
    $user_id = get_current_user_id();
    $favorites = get_user_meta($user_id, 'favorites', true) ?: [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $post_id = get_the_ID();

            // this is just if you want to show a heart icon if the post is favorited
            $favorited = false;
            if (in_array($post_id, $favorites)) {
                $favorited = true;
            }

            $results[] = [
                'title'   => get_the_title(),
                'link'    => get_permalink(),
                'id' => $post_id,
                'favorited' => $favorited,
                'author'  => get_field('author'),
                'content' => get_the_content(),
            ];
        }
    }

    wp_reset_postdata();

    wp_send_json([
        'results'        => $results,
        'max_num_pages'  => $query->max_num_pages,
    ]);
}
add_action('wp_ajax_filter_posts', __NAMESPACE__ . '\filter_posts_ajax');
add_action('wp_ajax_nopriv_filter_posts', __NAMESPACE__ . '\filter_posts_ajax');