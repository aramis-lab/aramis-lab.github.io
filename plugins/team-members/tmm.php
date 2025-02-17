<?php

/**
 * Plugin Name: Team Members
 * Plugin URI: https://wpdarko.com/team-members
 * Description: A responsive, simple and clean way to display your team. Create new members, add their positions, bios, social links and copy-paste the shortcode into any post/page. Find help and information on our <a href="https://wpdarko.com/support">support site</a>. This free version is NOT limited and does not contain any ad. Check out the <a href='https://wpdarko.com/team-members'>PRO version</a> for more great features.
 * Version: 5.3.5
 * Author: WP Darko
 * Author URI: https://wpdarko.com
 * Text Domain: team-members
 * Domain Path: /lang/
 * License: GPL2.
 */

/* Defines plugin's root folder. */
define('TMM_PATH', plugin_dir_path(__FILE__));

/* Defines plugin's text domain. */
define('TMM_TXTDM', 'team-members');

/* General. */
require_once 'inc/tmm-text-domain.php';
require_once 'inc/tmm-pro-version-check.php';

/* Scripts. */
require_once 'inc/tmm-front-scripts.php';
require_once 'inc/tmm-admin-scripts.php';

/* Teams. */
require_once 'inc/tmm-post-type.php';

/* Shortcode. */
require_once 'inc/tmm-shortcode-column.php';
require_once 'inc/tmm-shortcode.php';

/* Registers metaboxes. */
require_once 'inc/tmm-metaboxes-members.php';
require_once 'inc/tmm-metaboxes-settings.php';
require_once 'inc/tmm-metaboxes-help.php';
require_once 'inc/tmm-metaboxes-pro.php';

/* Saves metaboxes. */
require_once 'inc/tmm-save-metaboxes.php';
