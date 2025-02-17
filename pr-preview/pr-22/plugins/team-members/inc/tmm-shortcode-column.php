<?php

/* Handles shortcode column display. */
add_action('manage_tmm_posts_custom_column', 'tmm_custom_columns', 10, 2);
function tmm_custom_columns($column, $post_id)
{
  switch ($column) {
    case 'dk_shortcode':
      global $post;
      $slug = '';
      $slug = $post->post_name;
      $shortcode = '<span style="display:inline-block;border:solid 2px lightgray; background:white; padding:0 8px; font-size:13px; line-height:25px; vertical-align:middle;">[tmm name="' . $slug . '"]</span>';
      echo wp_kses_post($shortcode);
      break;
  }
}


/* Adds the shortcode column in admin. */
add_filter('manage_tmm_posts_columns', 'add_tmm_columns');
function add_tmm_columns($columns)
{
  return array_merge($columns, array('dk_shortcode' => 'Shortcode'));
}