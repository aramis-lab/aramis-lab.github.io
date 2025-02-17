<?php
/*
Plugin Name: SiteOrigin Installer
Plugin URI: https://siteorigin.com/installer/
Description: This plugin installs all the SiteOrigin themes and plugins you need to get started with your new site.
Author: SiteOrigin
Author URI: https://siteorigin.com
Version: 1.0.3
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

if ( ! defined( 'SITEORIGIN_INSTALLER_VERSION' ) ) {
	define( 'SITEORIGIN_INSTALLER_VERSION', '1.0.3' );
	define( 'SITEORIGIN_INSTALLER_DIR', plugin_dir_path( __FILE__ ) );
	define( 'SITEORIGIN_INSTALLER_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! class_exists( 'SiteOrigin_Installer' ) ) {
	class SiteOrigin_Installer {
		public function __construct() {
			add_filter( 'siteorigin_premium_affiliate_id', array( $this, 'affiliate_id' ) );
			add_filter( 'init', array( $this, 'setup' ) );
			add_filter( 'siteorigin_add_installer', array( $this, 'load_status' ) );
			add_action( 'wp_ajax_so_installer_status', array( $this, 'installer_status_ajax' ) );
		}

		public static function single() {
			static $single;

			return empty( $single ) ? $single = new self() : $single;
		}

		public static function user_has_permission() {
			return (
				! defined( 'DISALLOW_FILE_MODS' ) ||
				! DISALLOW_FILE_MODS
			) &&
			current_user_can( 'install_plugins' ) &&
			current_user_can( 'install_themes' ) &&
			current_user_can( 'update_themes' ) &&
			current_user_can( 'update_plugins' );
		}

		public function setup() {
			if (
				apply_filters( 'siteorigin_add_installer', true ) &&
				is_admin() &&
				self::user_has_permission()
			) {
				// If the installer has been installed as a plugin (rather than bundled), setup the Github updater.
				if ( basename( SITEORIGIN_INSTALLER_DIR ) == 'siteorigin-installer-develop' ) {
					require_once SITEORIGIN_INSTALLER_DIR . '/inc/github-plugin-updater.php';
					new SiteOrigin_Installer_GitHub_Updater( __FILE__ );
				}

				require_once __DIR__ . '/inc/admin.php';
			}
		}

		/**
		 * Get the Affiliate ID from the database.
		 *
		 * @return mixed|void
		 */
		public function affiliate_id( $id ) {
			if ( get_option( 'siteorigin_premium_affiliate_id' ) ) {
				$id = get_option( 'siteorigin_premium_affiliate_id' );
			}

			return $id;
		}

		public function load_status() {
			return (bool) get_option( 'siteorigin_installer', true );
		}

		public function installer_status_ajax () {
			check_ajax_referer( 'siteorigin_installer_status', 'nonce' );
			update_option( 'siteorigin_installer', rest_sanitize_boolean( $_POST['status'] ) );
			die();
		}
	}
}
SiteOrigin_Installer::single();
