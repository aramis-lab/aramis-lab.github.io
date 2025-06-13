<?php

/* Registers the teams post type. */
add_action('init', 'register_tmm_type');
function register_tmm_type()
{

	/* Defines labels. */
	$labels = array(
		'name'               => __('Teams', TMM_TXTDM),
		'singular_name'      => __('Team', TMM_TXTDM),
		'menu_name'          => __('Teams', TMM_TXTDM),
		'name_admin_bar'     => __('Team', TMM_TXTDM),
		'add_new'            => __('Add New', TMM_TXTDM),
		'add_new_item'       => __('Add New Team', TMM_TXTDM),
		'new_item'           => __('New Team', TMM_TXTDM),
		'edit_item'          => __('Edit Team', TMM_TXTDM),
		'view_item'          => __('View Team', TMM_TXTDM),
		'all_items'          => __('All Teams', TMM_TXTDM),
		'search_items'       => __('Search Teams', TMM_TXTDM),
		'not_found'          => __('No Teams found.', TMM_TXTDM),
		'not_found_in_trash' => __('No Teams found in Trash.', TMM_TXTDM)
	);

	/* Defines permissions. */
	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_admin_bar'  => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array('title'),
		'menu_icon'          => 'dashicons-plus'
	);

	/* Registers post type. */
	register_post_type('tmm', $args);
}

/* Customizes teams update messages. */
add_filter('post_updated_messages', 'tmm_updated_messages');
function tmm_updated_messages($messages)
{
	$post             = get_post();
	$post_type        = get_post_type($post);
	$post_type_object = get_post_type_object($post_type);

	/* Defines update messages. */
	$messages['tmm'] = array(
		1  => __('Team updated.', TMM_TXTDM),
		4  => __('Team updated.', TMM_TXTDM),
		6  => __('Team published.', TMM_TXTDM),
		7  => __('Team saved.', TMM_TXTDM),
		10 => __('Team draft updated.', TMM_TXTDM)
	);

	return $messages;
}