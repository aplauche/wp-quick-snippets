<?php 

/*  Kill attachment, search, author, daily archive pages
------------------------------------------------ */
add_action('template_redirect', 'custom_template_redirect');
function custom_template_redirect()
{
global $wp_query, $post;

if (is_author() || is_attachment() || is_day() || is_search())
{
  wp_redirect(get_option('home'));
  exit;
}

if (is_feed())
{
    $author     = get_query_var('author_name');
    $attachment = get_query_var('attachment');
    $attachment = (empty($attachment)) ? get_query_var('attachment_id') : $attachment;
    $day        = get_query_var('day');
    $search     = get_query_var('s');

    if (!empty($author) || !empty($attachment) || !empty($day) || !empty($search))
    {
        $wp_query->set_404();
        $wp_query->is_feed = false;
    }
  }
}
