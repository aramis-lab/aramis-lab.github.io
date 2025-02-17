<?php

if ( ! class_exists( 'SiteOrigin_Installer_Admin' ) ) {
	class SiteOrigin_Installer_Admin {
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
			add_action( 'wp_ajax_so_installer_dismiss', array( $this, 'dismiss_notice' ) );
			add_action( 'wp_ajax_siteorigin_installer_manage', array( $this, 'manage_product' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );
			add_action( 'activated_plugin', array( $this, 'maybe_clear_cache' ) );
			add_action( 'deactivated_plugin', array( $this, 'maybe_clear_cache' ) );
		}

		public static function single() {
			static $single;

			return empty( $single ) ? $single = new self() : $single;
		}

		public function maybe_clear_cache( $plugin ) {
			$products = apply_filters( 'siteorigin_installer_products_transient', get_transient( 'siteorigin_installer_product_data' ) );

			if ( ! empty( $products ) && ! empty( $products[ basename( $plugin, '.php' ) ] ) ) {
				delete_transient( 'siteorigin_installer_product_data' );
			}
		}

		public function display_admin_notices() {
			global $pagenow;

			if (
				! get_option( 'siteorigin_installer_admin_dismissed' ) &&
				current_user_can( 'install_plugins' ) &&
				(
					$pagenow != 'admin.php' ||
					(
						! empty( $_GET['page'] ) &&
						$_GET['page'] != 'siteorigin-installer'
					)
				)
			) {
				$dismiss_url = wp_nonce_url( add_query_arg( array( 'action' => 'so_installer_dismiss' ), admin_url( 'admin-ajax.php' ) ), 'so_installer_dismiss' );
				?>
				<div id="siteorigin-installer-notice" class="notice notice-warning">
					<p>
						<?php
						printf(
							__( '%s to install recommended SiteOrigin plugins and a SiteOrigin theme to get your site going.', 'siteorigin-installer' ),
							'<a href="' . esc_url( admin_url( 'admin.php?page=siteorigin-installer' ) ) . '" target="_blank" rel="noopener noreferrer" >' . __( 'Click here', 'siteorigin-installer' ) . '</a>'
						);
						?>
					</p>
					<a href="<?php echo esc_url( $dismiss_url ); ?>" class="siteorigin-notice-dismiss"></a>
				</div>
				<?php
				wp_enqueue_script(
					'siteorigin-installer-notice',
					SITEORIGIN_INSTALLER_URL . 'js/notices.js',
					array( 'jquery' ),
					SITEORIGIN_INSTALLER_VERSION
				);

				wp_enqueue_style(
					'siteorigin-installer-notice',
					SITEORIGIN_INSTALLER_URL . 'css/notices.css'
				);
			}
		}

		public function dismiss_notice() {
			check_ajax_referer( 'so_installer_dismiss' );
			update_option( 'siteorigin_installer_admin_dismissed', true );

			die();
		}

		public function admin_menu() {
			global $menu;

			if (
				! defined( 'SITEORIGIN_INSTALLER_THEME_MODE' ) &&
				empty( $GLOBALS['admin_page_hooks']['siteorigin'] )
			) {
				add_menu_page(
					__( 'SiteOrigin', 'siteorigin-installer' ),
					__( 'SiteOrigin', 'siteorigin-installer' ),
					false,
					'admin.php?page=siteorigin-installer',
					false,
					SITEORIGIN_INSTALLER_URL . '/img/icon.svg',
					66
				);
			}

			add_submenu_page(
				'siteorigin',
				__( 'Installer', 'siteorigin-installer' ),
				__( 'Installer', 'siteorigin-installer' ),
				'manage_options',
				'siteorigin-installer',
				array( $this, 'display_admin_page' )
			);
		}

		public function enqueue_scripts( $prefix ) {
			if (
				$prefix !== 'admin_page_siteorigin-installer' &&
				$prefix !== 'siteorigin_page_siteorigin-installer'
			) {
				return;
			}

			wp_enqueue_style(
				'siteorigin-installer',
				SITEORIGIN_INSTALLER_URL . '/css/admin.css',
				array(),
				SITEORIGIN_INSTALLER_VERSION
			);

			wp_enqueue_script(
				'siteorigin-installer',
				SITEORIGIN_INSTALLER_URL . '/js/script.js',
				array( 'jquery' ),
				SITEORIGIN_INSTALLER_VERSION
			);

			wp_localize_script(
				'siteorigin-installer',
				'soInstallerAdmin',
				array(
					'manageUrl' => wp_nonce_url( admin_url( 'admin-ajax.php?action=siteorigin_installer_manage' ), 'siteorigin-installer-manage' ),
					'activateText' => __( 'Activate', 'siteorigin-installer' ),
				)
			);
		}

		public function manage_product() {
			check_ajax_referer( 'siteorigin-installer-manage' );

			if ( empty( $_POST['slug'] ) || empty( $_POST['task'] ) || empty( $_POST['type'] ) || empty( $_POST['version'] ) ) {
				die();
			}

			$slug = sanitize_file_name( $_POST['slug'] );

			$product_url = 'https://wordpress.org/' . urlencode( $_POST['type'] ) . '/download/' . urlencode( $slug ) . '.' . urlencode( $_POST['version'] ) . '.zip';
			// check_ajax_referer( 'so_installer_manage' );
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			$upgrader = new WP_Upgrader();
			if ( $_POST['type'] == 'plugins' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => WP_PLUGIN_DIR,
						'clear_destination' => true,
						'abort_if_destination_exists' => false,
						'hook_extra' => array(
							'type' => 'plugin',
							'action' => 'install',
						),
					) );

					$clear = true;
				} elseif (
					$_POST['task'] == 'activate' &&
					! is_wp_error( validate_plugin( $slug . '/' . $slug . '.php' ) )
				) {
					activate_plugin( $slug . '/' . $slug . '.php' );
					$clear = true;
				}
			} elseif ( $_POST['type'] == 'themes' ) {
				if ( $_POST['task'] == 'install' || $_POST['task'] == 'update' ) {
					$upgrader->run( array(
						'package' => esc_url( $product_url ),
						'destination' => get_theme_root(),
						'clear_destination' => true,
						'clear_working' => true,
						'abort_if_destination_exists' => false,
					) );
					$clear = true;
				} elseif ( $_POST['task'] == 'activate' ) {
					switch_theme( $slug );
					$clear = true;
				}
			}

			if ( ! empty( $clear ) ) {
				delete_transient( 'siteorigin_installer_product_data' );
			}
			die();
		}

		private function update_product_data( $product = array(), $return = true ) {
			$current_theme = wp_get_theme();

			if ( empty( $products ) ) {
				$products = apply_filters( 'siteorigin_installer_products', json_decode( file_get_contents( SITEORIGIN_INSTALLER_DIR . '/data/products.json' ), true ) );
			}

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$fields = array(
				'short_description' => true,
				'version' => true,
			);

			// $product_data = get_transient( 'siteorigin_installer_product_data' );
			foreach ( $products as $slug => $item ) {
				$status = false;

				if ( $slug != 'siteorigin-premium' ) {
					if ( $item['type'] == 'themes' ) {
						$api = themes_api(
							'theme_information',
							array(
								'slug' => $slug,
								'fields' => $fields,
							)
						);

						$theme = wp_get_theme( $slug );

						// Work out the status of the theme.
						if ( is_object( $theme->errors() ) ) {
							$status = 'install';
						} else {
							$products[ $slug ]['update'] = version_compare( $theme->get( 'Version' ), $api->version, '<' );

							if ( $theme->get_stylesheet() != $current_theme->get_stylesheet() ) {
								$status = 'activate';
							}
						}

						// Theme descriptions are too long so we need to shorten them.
						$description = explode( '.', $api->sections['description'] );
						$description = $description[0] . '. ' . $description[1];
					} else {
						$api = plugins_api(
							'plugin_information',
							array(
								'slug' => $slug,
								'fields' => $fields,
							)
						);

						// Work out the status of the plugin.
						$plugin_file = "$slug/$slug.php";

						if ( ! file_exists( WP_PLUGIN_DIR . "/$plugin_file" ) ) {
							$status = 'install';
						} elseif ( ! is_plugin_active( $plugin_file ) ) {
							$status = 'activate';
						}

						if ( $status != 'install' ) {
							$plugin = get_plugin_data( WP_PLUGIN_DIR . "/$plugin_file" );
							$products[ $slug ]['update'] = version_compare( $plugin['Version'], $api->version, '<' );
						}

						if ( empty( $item['screenshot'] ) ) {
							$products[ $slug ]['screenshot'] = 'https://plugins.svn.wordpress.org/' . $slug . '/assets/icon.svg';
						}
					}

					$products[ $slug ]['status'] = $status;
					$products[ $slug ]['version'] = $api->version;

					if ( empty( $item['description'] ) ) {
						$products[ $slug ]['description'] = $item['type'] == 'themes' ? "$description." : $api->short_description;
					}
				} elseif (
					! apply_filters( 'siteorigin_premium_upgrade_teaser', true ) ||
					defined( 'SITEORIGIN_PREMIUM_VERSION' )
				) {
					unset( $products['siteorigin-premium'] );
				} else {
					$products['siteorigin-premium']['screenshot'] = SITEORIGIN_INSTALLER_URL . 'img/premium-icon.svg';
				}
			}
			uasort( $products, array( $this, 'sort_compare' ) );
			set_transient( 'siteorigin_installer_product_data', $products, 43200 );

			return $products;
		}

		/**
		 * Display the theme admin page
		 */
		public function display_admin_page() {
			$products = apply_filters( 'siteorigin_installer_products_transient', get_transient( 'siteorigin_installer_product_data' ) );

			if ( empty( $products ) ) {
				$products = $this->update_product_data();
			}

			if ( ! empty( $_GET['highlight'] ) && isset( $products[ (string) $_GET['highlight'] ] ) ) {
				$products[ (string) $_GET['highlight'] ]['weight'] = 9999;
				uasort( $products, array( $this, 'sort_compare' ) );
				$highlight = $_GET['highlight'];
			}

			include SITEORIGIN_INSTALLER_DIR . '/tpl/admin.php';
		}

		/**
		 * Comparison function for sorting
		 *
		 * @return int
		 */
		public function sort_compare( $a, $b ) {
			if ( empty( $a['weight'] ) || empty( $b['weight'] ) ) {
				return 0;
			}

			return $a['weight'] < $b['weight'] ? 1 : -1;
		}
	}
}
SiteOrigin_Installer_Admin::single();
