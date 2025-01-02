<?php

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
        array(
            'key' => '{custom_field_key}',
            'value' => '{custom_field_value}',
            'compare' => '='
        ),
    ),
    'tax_query' => array(
        array(
            'taxonomy' => '{custom_taxonomy_slug}',
            'field' => 'slug',
            'terms' => '{custom_term_slug}',
        ),
    ),
);

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        // Display post content
        the_title('<h2>', '</h2>');
        the_excerpt();
    endwhile;
    wp_reset_postdata();
else :
    echo '<p>No posts found</p>';
endif;

wp_reset_postdata();
wp_reset_query();
?>
