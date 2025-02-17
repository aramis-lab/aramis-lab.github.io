<?php
/**
 * #####################################################################
 * ### SLIDE ANYTHING PLUGIN - PHP FUNCTIONS FOR WordPress FRONT-END ###
 * #####################################################################
 *
 * @package     WordPress_Slide_Anything
 * @author      Simon Edge
 * @copyright   EdgeWebPages
 * @license     GPLv2 or later
 */

add_shortcode( 'slide-anything', 'slide_anything_shortcode' );

/**
 * ##### ROOT FUNCTION THAT IS CALLED TO BY THE 'slide-anything' SHORTCODE #####
 *
 * @param array $atts Shortcode attributes.
 */
function slide_anything_shortcode( $atts ) {
	wp_enqueue_script( 'jquery' );
	wp_register_script( 'owl_carousel_js', SA_PLUGIN_PATH . 'owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2.2.1', true );
	wp_enqueue_script( 'owl_carousel_js' );
	wp_register_style( 'owl_carousel_css', SA_PLUGIN_PATH . 'owl-carousel/owl.carousel.css', array(), '2.2.1.1', 'all' );
	wp_enqueue_style( 'owl_carousel_css' );
	wp_register_style( 'owl_theme_css', SA_PLUGIN_PATH . 'owl-carousel/sa-owl-theme.css', array(), '2.0', 'all' );
	wp_enqueue_style( 'owl_theme_css' );
	wp_register_style( 'owl_animate_css', SA_PLUGIN_PATH . 'owl-carousel/animate.min.css', array(), '2.0', 'all' );
	wp_enqueue_style( 'owl_animate_css' );
	wp_register_script( 'mousewheel_js', SA_PLUGIN_PATH . 'js/jquery.mousewheel.min.js', array( 'jquery' ), '3.1.13', true );
	wp_enqueue_script( 'mousewheel_js' );
	wp_register_script( 'owl_thumbs_js', SA_PLUGIN_PATH . 'owl-carousel/owl.carousel2.thumbs.min.js', array( 'jquery' ), '0.1.8', true );
	wp_enqueue_script( 'owl_thumbs_js' );
	// JAVASCRIPT/CSS FOR LIGHTGALLERY (POPUP) LIBRARY (lightgalleryjs.com).
	wp_register_script( 'lightgallery_js', SA_PLUGIN_PATH . 'lightgallery/lightgallery.min.js', array( 'jquery' ), '2.5.0', true );
	wp_enqueue_script( 'lightgallery_js' );
	wp_register_script( 'lightgallery_video_js', SA_PLUGIN_PATH . 'lightgallery/plugins/video/lg-video.min.js', array( 'jquery' ), '2.5.0', true );
	wp_enqueue_script( 'lightgallery_video_js' );
	wp_register_script( 'lightgallery_zoom_js', SA_PLUGIN_PATH . 'lightgallery/plugins/zoom/lg-zoom.min.js', array( 'jquery' ), '2.5.0', true );
	wp_enqueue_script( 'lightgallery_zoom_js' );
	wp_register_script( 'lightgallery_autoplay_js', SA_PLUGIN_PATH . 'lightgallery/plugins/autoplay/lg-autoplay.min.js', array( 'jquery' ), '2.5.0', true );
	wp_enqueue_script( 'lightgallery_autoplay_js' );
	wp_register_script( 'vimeo_player_js', SA_PLUGIN_PATH . 'lightgallery/player.min.js', array( 'jquery' ), '2.17.1', true );
	wp_enqueue_script( 'vimeo_player_js' );
	wp_register_style( 'lightgallery_css', SA_PLUGIN_PATH . 'lightgallery/css/lightgallery.css', array(), '2.5.0', 'all' );
	wp_enqueue_style( 'lightgallery_css' );
	wp_register_style( 'lightgallery_bundle_css', SA_PLUGIN_PATH . 'lightgallery/css/lightgallery-bundle.min.css', array(), '2.5.0', 'all' );
	wp_enqueue_style( 'lightgallery_bundle_css' );

	// EXTRACT SHORTCODE ATTRIBUTES.
	$args   = shortcode_atts( array( 'id' => '0' ), $atts );
	$id     = (int) $args['id'];
	$output = '';
	if ( 0 === $id ) {
		// SHORTCODE 'id' PARAMETER PROVIDED IS INVALID.
		$output .= "<div id='sa_invalid_postid'>Slide Anything shortcode error: A valid ID has not been provided</div>\n";
	} else {
		$post_status = get_post_status( $id );
		if ( 'publish' === $post_status ) {
			$metadata  = get_metadata( 'post', $id );
			$post_type = get_post_type( $id );
		}
		if ( ( 'publish' !== $post_status ) || ( 0 === count( $metadata ) ) || ( 'sa_slider' !== $post_type ) ) {
			// SHORTCODE 'id' PARAMETER PROVIDED IS INVALID.
			$output .= "<div id='sa_invalid_postid'>Slide Anything shortcode error: A valid ID has not been provided</div>\n";
		} else {
			// VALID 'id' PROVIDED - PROCESS SHORTCODE.
			// GET SLIDE DATA FROM DATABASE AND SAVE IN ARRAY.
			$slide_data               = array();
			$slide_data['num_slides'] = $metadata['sa_num_slides'][0];
			$slide_data['shortcodes'] = $metadata['sa_shortcodes'][0];
			if ( '1' === $slide_data['shortcodes'] ) {
				$slide_data['shortcodes'] = 'true';
			} else {
				$slide_data['shortcodes'] = 'false';
			}
			$slide_data['css_id'] = $metadata['sa_css_id'][0];
			for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
				$slide_data[ 'slide' . $i . '_num' ] = $i;
				// apply 'the_content' filter to slide content to process any shortcodes.
				if ( 'true' === $slide_data['shortcodes'] ) {
					$slide_data[ 'slide' . $i . '_content' ] = do_shortcode( $metadata[ 'sa_slide' . $i . '_content' ][0] );
				} else {
					$slide_data[ 'slide' . $i . '_content' ] = $metadata[ 'sa_slide' . $i . '_content' ][0];
				}
				$slide_image_data = '';
				if ( isset( $metadata[ 'sa_slide' . $i . '_image_data' ] ) ) {
					$slide_image_data = $metadata[ 'sa_slide' . $i . '_image_data' ][0];
				}
				if ( isset( $slide_image_data ) && ( '' !== $slide_image_data ) ) {
					$data_arr                                     = explode( '~', $slide_image_data );
					$slide_data[ 'slide' . $i . '_image_id' ]     = $data_arr[0];
					$slide_data[ 'slide' . $i . '_image_pos' ]    = $data_arr[1];
					$slide_data[ 'slide' . $i . '_image_size' ]   = $data_arr[2];
					$slide_data[ 'slide' . $i . '_image_repeat' ] = $data_arr[3];
					$slide_data[ 'slide' . $i . '_image_color' ]  = $data_arr[4];
				} else {
					$slide_data[ 'slide' . $i . '_image_id' ]     = $metadata[ 'sa_slide' . $i . '_image_id' ][0];
					$slide_data[ 'slide' . $i . '_image_pos' ]    = $metadata[ 'sa_slide' . $i . '_image_pos' ][0];
					$slide_data[ 'slide' . $i . '_image_size' ]   = $metadata[ 'sa_slide' . $i . '_image_size' ][0];
					$slide_data[ 'slide' . $i . '_image_repeat' ] = $metadata[ 'sa_slide' . $i . '_image_repeat' ][0];
					$slide_data[ 'slide' . $i . '_image_color' ]  = $metadata[ 'sa_slide' . $i . '_image_color' ][0];
				}
				$slide_data[ 'slide' . $i . '_link_url' ]    = $metadata[ 'sa_slide' . $i . '_link_url' ][0];
				$slide_data[ 'slide' . $i . '_link_target' ] = $metadata[ 'sa_slide' . $i . '_link_target' ][0];
				if ( '' === $slide_data[ 'slide' . $i . '_link_target' ] ) {
					$slide_data[ 'slide' . $i . '_link_target' ] = '_self';
				}
				// ### GET POPUP DATA ###
				$slide_data[ 'slide' . $i . '_popup_type' ]       = 'NONE';
				$slide_data[ 'slide' . $i . '_popup_imageid' ]    = '';
				$slide_data[ 'slide' . $i . '_popup_imagetitle' ] = '';
				$slide_data[ 'slide' . $i . '_popup_video_id' ]   = '';
				$slide_data[ 'slide' . $i . '_popup_video_type' ] = '';
				$slide_data[ 'slide' . $i . '_popup_html' ]       = '';
				$slide_data[ 'slide' . $i . '_popup_shortcode' ]  = '';
				$slide_data[ 'slide' . $i . '_popup_bgcol' ]      = '#ffffff';
				$slide_data[ 'slide' . $i . '_popup_width' ]      = '600';
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_type' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_type' ] = $metadata[ 'sa_slide' . $i . '_popup_type' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_imageid' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_imageid' ] = $metadata[ 'sa_slide' . $i . '_popup_imageid' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_imagetitle' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_imagetitle' ] = $metadata[ 'sa_slide' . $i . '_popup_imagetitle' ][0];
				}
				$slide_data[ 'slide' . $i . '_popup_image' ]      = '';
				$slide_data[ 'slide' . $i . '_popup_background' ] = 'no';
				if ( 'IMAGE' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					if ( ( '' !== $slide_data[ 'slide' . $i . '_popup_imageid' ] ) && ( 0 !== $slide_data[ 'slide' . $i . '_popup_imageid' ] ) ) {
						$popup_full_images                                = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_popup_imageid' ], 'full' );
						$slide_data[ 'slide' . $i . '_popup_image' ]      = $popup_full_images[0];
						$slide_data[ 'slide' . $i . '_popup_background' ] = $metadata[ 'sa_slide' . $i . '_popup_background' ][0];
						if ( '' === $slide_data[ 'slide' . $i . '_popup_background' ] ) {
							$slide_data[ 'slide' . $i . '_popup_background' ] = 'no';
						}
					}
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_video_id' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_video_id' ] = $metadata[ 'sa_slide' . $i . '_popup_video_id' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_video_type' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_video_type' ] = $metadata[ 'sa_slide' . $i . '_popup_video_type' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_html' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_html' ] = $metadata[ 'sa_slide' . $i . '_popup_html' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_shortcode' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_shortcode' ] = $metadata[ 'sa_slide' . $i . '_popup_shortcode' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_bgcol' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_bgcol' ] = $metadata[ 'sa_slide' . $i . '_popup_bgcol' ][0];
				}
				if ( isset( $metadata[ 'sa_slide' . $i . '_popup_width' ] ) ) {
					$slide_data[ 'slide' . $i . '_popup_width' ] = $metadata[ 'sa_slide' . $i . '_popup_width' ][0];
				}
				if ( 'HTML' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					$slide_data[ 'slide' . $i . '_popup_css_id' ] = $slide_data['css_id'] . '_popup' . $i;
				} else {
					$slide_data[ 'slide' . $i . '_popup_css_id' ] = '';
				}
			}
			$slide_data['slide_duration']   = floatval( $metadata['sa_slide_duration'][0] ) * 1000;
			$slide_data['slide_transition'] = floatval( $metadata['sa_slide_transition'][0] ) * 1000;
			if ( isset( $metadata['sa_slide_by'][0] ) && ( '' !== $metadata['sa_slide_by'][0] ) ) {
				$slide_data['slide_by'] = $metadata['sa_slide_by'][0];
				if ( '0' === $slide_data['slide_by'] ) {
					$slide_data['slide_by'] = 'page';
				}
			} else {
				$slide_data['slide_by'] = 1;
			}
			$slide_data['loop_slider'] = $metadata['sa_loop_slider'][0];
			if ( '1' === $slide_data['loop_slider'] ) {
				$slide_data['loop_slider'] = 'true';
			} else {
				$slide_data['loop_slider'] = 'false';
			}
			$slide_data['stop_hover'] = $metadata['sa_stop_hover'][0];
			if ( '1' === $slide_data['stop_hover'] ) {
				$slide_data['stop_hover'] = 'true';
			} else {
				$slide_data['stop_hover'] = 'false';
			}
			$slide_data['random_order'] = $metadata['sa_random_order'][0];
			if ( '1' === $slide_data['random_order'] ) {
				$slide_data['random_order'] = 'true';
			} else {
				$slide_data['random_order'] = 'false';
			}
			$slide_data['reverse_order'] = $metadata['sa_reverse_order'][0];
			if ( '1' === $slide_data['reverse_order'] ) {
				$slide_data['reverse_order'] = 'true';
			} else {
				$slide_data['reverse_order'] = 'false';
			}
			$slide_data['nav_arrows'] = $metadata['sa_nav_arrows'][0];
			if ( '1' === $slide_data['nav_arrows'] ) {
				$slide_data['nav_arrows'] = 'true';
			} else {
				$slide_data['nav_arrows'] = 'false';
			}
			$slide_data['pagination'] = $metadata['sa_pagination'][0];
			if ( '1' === $slide_data['pagination'] ) {
				$slide_data['pagination'] = 'true';
			} else {
				$slide_data['pagination'] = 'false';
			}
			$slide_data['mouse_drag'] = $metadata['sa_mouse_drag'][0];
			if ( '1' === $slide_data['mouse_drag'] ) {
				$slide_data['mouse_drag'] = 'true';
			} else {
				$slide_data['mouse_drag'] = 'false';
			}
			$slide_data['touch_drag'] = $metadata['sa_touch_drag'][0];
			if ( '1' === $slide_data['touch_drag'] ) {
				$slide_data['touch_drag'] = 'true';
			} else {
				$slide_data['touch_drag'] = 'false';
			}
			if ( isset( $metadata['sa_mousewheel'] ) ) {
				$slide_data['mousewheel'] = $metadata['sa_mousewheel'][0];
				if ( '1' === $slide_data['mousewheel'] ) {
					$slide_data['mousewheel'] = 'true';
				} else {
					$slide_data['mousewheel'] = 'false';
				}
			} else {
				$slide_data['mousewheel'] = 'false';
			}
			if ( isset( $metadata['sa_click_advance'] ) ) {
				$slide_data['click_advance'] = $metadata['sa_click_advance'][0];
				if ( '1' === $slide_data['click_advance'] ) {
					$slide_data['click_advance'] = 'true';
				} else {
					$slide_data['click_advance'] = 'false';
				}
			} else {
				$slide_data['click_advance'] = 'false';
			}
			if ( isset( $metadata['sa_auto_height'] ) ) {
				$slide_data['auto_height'] = $metadata['sa_auto_height'][0];
				if ( '1' === $slide_data['auto_height'] ) {
					$slide_data['auto_height'] = 'true';
				} else {
					$slide_data['auto_height'] = 'false';
				}
			} else {
				$slide_data['auto_height'] = 'false';
			}
			if ( ( '0' === $metadata['sa_slide_min_height_perc'][0] ) || ( '0px' === $metadata['sa_slide_min_height_perc'][0] ) ) {
				$slide_data['vert_center'] = 'false';
			} else {
				if ( isset( $metadata['sa_vert_center'] ) ) {
					$slide_data['vert_center'] = $metadata['sa_vert_center'][0];
					if ( '1' === $slide_data['vert_center'] ) {
						$slide_data['vert_center'] = 'true';
					} else {
						$slide_data['vert_center'] = 'false';
					}
				} else {
					$slide_data['vert_center'] = 'false';
				}
			}
			$slide_data['items_width1'] = $metadata['sa_items_width1'][0];
			$slide_data['items_width2'] = $metadata['sa_items_width2'][0];
			$slide_data['items_width3'] = $metadata['sa_items_width3'][0];
			$slide_data['items_width4'] = $metadata['sa_items_width4'][0];
			$slide_data['items_width5'] = $metadata['sa_items_width5'][0];
			$slide_data['items_width6'] = $metadata['sa_items_width6'][0];
			if ( '' === $slide_data['items_width6'] ) {
				$slide_data['items_width6'] = $slide_data['items_width5'];
			}
			$slide_data['transition']            = $metadata['sa_transition'][0];
			$slide_data['background_color']      = $metadata['sa_background_color'][0];
			$slide_data['border_width']          = $metadata['sa_border_width'][0];
			$slide_data['border_color']          = $metadata['sa_border_color'][0];
			$slide_data['border_radius']         = $metadata['sa_border_radius'][0];
			$slide_data['wrapper_padd_top']      = $metadata['sa_wrapper_padd_top'][0];
			$slide_data['wrapper_padd_right']    = $metadata['sa_wrapper_padd_right'][0];
			$slide_data['wrapper_padd_bottom']   = $metadata['sa_wrapper_padd_bottom'][0];
			$slide_data['wrapper_padd_left']     = $metadata['sa_wrapper_padd_left'][0];
			$slide_data['slide_min_height_perc'] = $metadata['sa_slide_min_height_perc'][0];
			$slide_data['slide_padding_tb']      = $metadata['sa_slide_padding_tb'][0];
			$slide_data['slide_padding_lr']      = $metadata['sa_slide_padding_lr'][0];
			$slide_data['slide_margin_lr']       = $metadata['sa_slide_margin_lr'][0];
			$slide_data['slide_icons_location']  = $metadata['sa_slide_icons_location'][0];
			$slide_data['autohide_arrows']       = $metadata['sa_autohide_arrows'][0];
			if ( '1' === $slide_data['autohide_arrows'] ) {
				$slide_data['autohide_arrows'] = 'true';
			} else {
				$slide_data['autohide_arrows'] = 'false';
			}
			$slide_data['dot_per_slide'] = '0';
			if ( isset( $metadata['sa_dot_per_slide'] ) ) {
				$slide_data['dot_per_slide'] = $metadata['sa_dot_per_slide'][0];
				if ( '1' !== $slide_data['dot_per_slide'] ) {
					$slide_data['dot_per_slide'] = '0';
				}
			} else {
				$slide_data['dot_per_slide'] = '0';
			}
			$slide_data['slide_icons_visible'] = $metadata['sa_slide_icons_visible'][0];
			if ( '1' === $slide_data['slide_icons_visible'] ) {
				$slide_data['slide_icons_visible'] = 'true';
			} else {
				$slide_data['slide_icons_visible'] = 'false';
			}
			$slide_data['slide_icons_color'] = $metadata['sa_slide_icons_color'][0];
			if ( 'black' !== $slide_data['slide_icons_color'] ) {
				$slide_data['slide_icons_color'] = 'white';
			}
			if ( isset( $metadata['sa_slide_icons_fullslide'][0] ) &&
				( '1' === $metadata['sa_slide_icons_fullslide'][0] ) ) {
				$slide_data['slide_icons_fullslide'] = '1';
			} else {
				$slide_data['slide_icons_fullslide'] = '0';
			}
			// FETCH OTHER SETTINGS POST META.
			$other_settings = '';
			if ( isset( $metadata['sa_other_settings'] ) ) {
				$other_settings = $metadata['sa_other_settings'][0];
				if ( isset( $other_settings ) && ( '' !== $other_settings ) ) {
					$other_settings_arr = explode( '|', $other_settings );
				}
			}
			// setting 1 - sa_window_onload.
			$slide_data['sa_window_onload'] = '0';
			if ( isset( $other_settings_arr ) && ( '' !== $other_settings_arr[0] ) ) {
				$slide_data['sa_window_onload'] = $other_settings_arr[0];
			} else {
				if ( isset( $metadata['sa_window_onload'] ) ) {
					$slide_data['sa_window_onload'] = $metadata['sa_window_onload'][0];
					if ( '1' !== $slide_data['sa_window_onload'] ) {
						$slide_data['sa_window_onload'] = '0';
					}
				}
			}
			// setting 2 - sa_strip_javascript.
			$slide_data['strip_javascript'] = '0';
			// setting 3 - sa_lazy_load_images.
			$slide_data['lazy_load_images'] = '0';
			if ( isset( $other_settings_arr ) && ( '' !== $other_settings_arr[2] ) ) {
				$slide_data['lazy_load_images'] = $other_settings_arr[2];
			} else {
				if ( isset( $metadata['sa_lazy_load_images'] ) ) {
					$slide_data['lazy_load_images'] = $metadata['sa_lazy_load_images'][0];
					if ( '1' !== $slide_data['lazy_load_images'] ) {
						$slide_data['lazy_load_images'] = '0';
					}
				}
			}
			// setting 4 - sa_ulli_containers.
			$slide_data['ulli_containers'] = '0';
			if ( isset( $other_settings_arr ) && ( '' !== $other_settings_arr[3] ) ) {
				$slide_data['ulli_containers'] = $other_settings_arr[3];
			} else {
				if ( isset( $metadata['sa_ulli_containers'] ) ) {
					$slide_data['ulli_containers'] = $metadata['sa_ulli_containers'][0];
					if ( '1' !== $slide_data['ulli_containers'] ) {
						$slide_data['ulli_containers'] = '0';
					}
				}
			}
			// setting 5 - sa_rtl_slider.
			$slide_data['rtl_slider'] = '0';
			if ( isset( $other_settings_arr ) && ( '' !== $other_settings_arr[4] ) ) {
				$slide_data['rtl_slider'] = $other_settings_arr[4];
			}
			// setting 7 - bg_image_size.
			$slide_data['bg_image_size'] = 'full';
			if ( isset( $other_settings_arr ) && ( count( $other_settings_arr ) > 6 ) ) {
				if ( '' !== $other_settings_arr[6] ) {
					$slide_data['bg_image_size'] = $other_settings_arr[6];
				}
			}
			// setting 8 - disable_slide_ids.
			$slide_data['disable_slide_ids'] = '0';
			if ( isset( $other_settings_arr ) && ( count( $other_settings_arr ) > 7 ) ) {
				if ( '' !== $other_settings_arr[7] ) {
					$slide_data['disable_slide_ids'] = $other_settings_arr[7];
				}
			}
			// Start Position.
			$slide_data['start_pos'] = 0;
			if ( isset( $metadata['sa_start_pos'] ) ) {
				$slide_data['start_pos'] = $metadata['sa_start_pos'][0];
				if ( '' !== $slide_data['start_pos'] ) {
					$slide_data['start_pos'] = abs( intval( $slide_data['start_pos'] ) );
					if ( $slide_data['start_pos'] > 0 ) {
						$slide_data['start_pos'] = $slide_data['start_pos'] - 1;
					}
				}
			}

			// hero slider and slider thumbnails.
			$slide_data['hero_slider']   = '0';
			$slide_data['thumbs_active'] = '0';
			if ( isset( $metadata['sa_hero_slider'] ) ) {
				$slide_data['hero_slider'] = $metadata['sa_hero_slider'][0];
				if ( '1' !== $slide_data['hero_slider'] ) {
					$slide_data['hero_slider'] = '0';
				}
			} else {
				$slide_data['hero_slider'] = '0';
			}
			if ( isset( $metadata['sa_thumbs_active'] ) ) {
				$slide_data['thumbs_active'] = $metadata['sa_thumbs_active'][0];
				if ( '1' !== $slide_data['thumbs_active'] ) {
					$slide_data['thumbs_active'] = '0';
				}
			} else {
				$slide_data['thumbs_active'] = '0';
			}
			if ( isset( $metadata['sa_thumbs_location'] ) ) {
				$slide_data['thumbs_location'] = $metadata['sa_thumbs_location'][0];
			} else {
				$slide_data['thumbs_location'] = 'inside_bottom';
			}
			if ( isset( $metadata['sa_thumbs_image_size'] ) ) {
				$slide_data['thumbs_image_size'] = $metadata['sa_thumbs_image_size'][0];
			} else {
				$slide_data['thumbs_image_size'] = 'thumbnail';
			}
			if ( isset( $metadata['sa_thumbs_padding'] ) ) {
				$slide_data['thumbs_padding'] = $metadata['sa_thumbs_padding'][0];
			} else {
				$slide_data['thumbs_padding'] = '3';
			}
			if ( isset( $metadata['sa_thumbs_width'] ) ) {
				$slide_data['thumbs_width'] = $metadata['sa_thumbs_width'][0];
			} else {
				$slide_data['thumbs_width'] = '150';
			}
			if ( isset( $metadata['sa_thumbs_height'] ) ) {
				$slide_data['thumbs_height'] = $metadata['sa_thumbs_height'][0];
			} else {
				$slide_data['thumbs_height'] = '85';
			}
			if ( isset( $metadata['sa_thumbs_opacity'] ) ) {
				$slide_data['thumbs_opacity'] = $metadata['sa_thumbs_opacity'][0];
			} else {
				$slide_data['thumbs_opacity'] = '50';
			}
			if ( isset( $metadata['sa_thumbs_border_width'] ) ) {
				$slide_data['thumbs_border_width'] = $metadata['sa_thumbs_border_width'][0];
			} else {
				$slide_data['thumbs_border_width'] = '0';
			}
			if ( isset( $metadata['sa_thumbs_border_color'] ) ) {
				$slide_data['thumbs_border_color'] = $metadata['sa_thumbs_border_color'][0];
			} else {
				$slide_data['thumbs_border_color'] = '#ffffff';
			}
			if ( isset( $metadata['sa_thumbs_resp_tablet'] ) ) {
				$slide_data['thumbs_resp_tablet'] = $metadata['sa_thumbs_resp_tablet'][0];
			} else {
				$slide_data['thumbs_resp_tablet'] = '75';
			}
			if ( isset( $metadata['sa_thumbs_resp_mobile'] ) ) {
				$slide_data['thumbs_resp_mobile'] = $metadata['sa_thumbs_resp_mobile'][0];
			} else {
				$slide_data['thumbs_resp_mobile'] = '50';
			}
			// showcase carousel.
			$slide_data['showcase_slider'] = '0';
			if ( isset( $metadata['sa_showcase_slider'] ) ) {
				$slide_data['showcase_slider'] = $metadata['sa_showcase_slider'][0];
				if ( '1' !== $slide_data['showcase_slider'] ) {
					$slide_data['showcase_slider'] = '0';
				}
			} else {
				$slide_data['showcase_slider'] = '0';
			}
			if ( isset( $metadata['sa_showcase_width'] ) ) {
				$slide_data['showcase_width'] = $metadata['sa_showcase_width'][0];
			} else {
				$slide_data['showcase_width'] = '120';
			}
			if ( isset( $metadata['sa_showcase_tablet'] ) ) {
				$slide_data['showcase_tablet'] = $metadata['sa_showcase_tablet'][0];
				if ( '1' !== $slide_data['showcase_tablet'] ) {
					$slide_data['showcase_tablet'] = '0';
				}
			} else {
				$slide_data['showcase_tablet'] = '0';
			}
			if ( isset( $metadata['sa_showcase_width_tab'] ) ) {
				$slide_data['showcase_width_tab'] = $metadata['sa_showcase_width_tab'][0];
			} else {
				$slide_data['showcase_width_tab'] = '130';
			}
			if ( isset( $metadata['sa_showcase_mobile'] ) ) {
				$slide_data['showcase_mobile'] = $metadata['sa_showcase_mobile'][0];
				if ( '1' !== $slide_data['showcase_mobile'] ) {
					$slide_data['showcase_mobile'] = '0';
				}
			} else {
				$slide_data['showcase_mobile'] = '0';
			}
			if ( isset( $metadata['sa_showcase_width_mob'] ) ) {
				$slide_data['showcase_width_mob'] = $metadata['sa_showcase_width_mob'][0];
			} else {
				$slide_data['showcase_width_mob'] = '140';
			}

			// REVERSE THE ORDER OF THE SLIDES IF 'Random Order' CHECKBOX IS CHECKED OR
			// RE-ORDER SLIDES IN A RANDOM ORDER IF 'Random Order' CHECKBOX IS CHECKED.
			if ( ( 'true' === $slide_data['reverse_order'] ) || ( 'true' === $slide_data['random_order'] ) ) {
				$reorder_arr = array();
				for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
					$reorder_arr[ $i - 1 ]['num']              = $slide_data[ 'slide' . $i . '_num' ];
					$reorder_arr[ $i - 1 ]['content']          = $slide_data[ 'slide' . $i . '_content' ];
					$reorder_arr[ $i - 1 ]['image_id']         = $slide_data[ 'slide' . $i . '_image_id' ];
					$reorder_arr[ $i - 1 ]['image_pos']        = $slide_data[ 'slide' . $i . '_image_pos' ];
					$reorder_arr[ $i - 1 ]['image_size']       = $slide_data[ 'slide' . $i . '_image_size' ];
					$reorder_arr[ $i - 1 ]['image_repeat']     = $slide_data[ 'slide' . $i . '_image_repeat' ];
					$reorder_arr[ $i - 1 ]['image_color']      = $slide_data[ 'slide' . $i . '_image_color' ];
					$reorder_arr[ $i - 1 ]['link_url']         = $slide_data[ 'slide' . $i . '_link_url' ];
					$reorder_arr[ $i - 1 ]['link_target']      = $slide_data[ 'slide' . $i . '_link_target' ];
					$reorder_arr[ $i - 1 ]['popup_type']       = $slide_data[ 'slide' . $i . '_popup_type' ];
					$reorder_arr[ $i - 1 ]['popup_imageid']    = $slide_data[ 'slide' . $i . '_popup_imageid' ];
					$reorder_arr[ $i - 1 ]['popup_imagetitle'] = $slide_data[ 'slide' . $i . '_popup_imagetitle' ];
					$reorder_arr[ $i - 1 ]['popup_image']      = $slide_data[ 'slide' . $i . '_popup_image' ];
					$reorder_arr[ $i - 1 ]['popup_background'] = $slide_data[ 'slide' . $i . '_popup_background' ];
					$reorder_arr[ $i - 1 ]['popup_video_id']   = $slide_data[ 'slide' . $i . '_popup_video_id' ];
					$reorder_arr[ $i - 1 ]['popup_video_type'] = $slide_data[ 'slide' . $i . '_popup_video_type' ];
					$reorder_arr[ $i - 1 ]['popup_html']       = $slide_data[ 'slide' . $i . '_popup_html' ];
					$reorder_arr[ $i - 1 ]['popup_shortcode']  = $slide_data[ 'slide' . $i . '_popup_shortcode' ];
					$reorder_arr[ $i - 1 ]['popup_bgcol']      = $slide_data[ 'slide' . $i . '_popup_bgcol' ];
					$reorder_arr[ $i - 1 ]['popup_width']      = $slide_data[ 'slide' . $i . '_popup_width' ];
					$reorder_arr[ $i - 1 ]['popup_css_id']     = $slide_data[ 'slide' . $i . '_popup_css_id' ];
				}
				if ( 'true' === $slide_data['random_order'] ) {
					// SORT SLIDE ARRAY DATA IN A RANDOM ORDER.
					shuffle( $reorder_arr );
				} else {
					// REVERSE THE ORDER OF THE SLIDE DATA ARRAY.
					$reverse_arr = array_reverse( $reorder_arr );
					$reorder_arr = $reverse_arr;
				}
				for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
					$slide_data[ 'slide' . $i . '_num' ]              = $reorder_arr[ $i - 1 ]['num'];
					$slide_data[ 'slide' . $i . '_content' ]          = $reorder_arr[ $i - 1 ]['content'];
					$slide_data[ 'slide' . $i . '_image_id' ]         = $reorder_arr[ $i - 1 ]['image_id'];
					$slide_data[ 'slide' . $i . '_image_pos' ]        = $reorder_arr[ $i - 1 ]['image_pos'];
					$slide_data[ 'slide' . $i . '_image_size' ]       = $reorder_arr[ $i - 1 ]['image_size'];
					$slide_data[ 'slide' . $i . '_image_repeat' ]     = $reorder_arr[ $i - 1 ]['image_repeat'];
					$slide_data[ 'slide' . $i . '_image_color' ]      = $reorder_arr[ $i - 1 ]['image_color'];
					$slide_data[ 'slide' . $i . '_link_url' ]         = $reorder_arr[ $i - 1 ]['link_url'];
					$slide_data[ 'slide' . $i . '_link_target' ]      = $reorder_arr[ $i - 1 ]['link_target'];
					$slide_data[ 'slide' . $i . '_popup_type' ]       = $reorder_arr[ $i - 1 ]['popup_type'];
					$slide_data[ 'slide' . $i . '_popup_imageid' ]    = $reorder_arr[ $i - 1 ]['popup_imageid'];
					$slide_data[ 'slide' . $i . '_popup_imagetitle' ] = $reorder_arr[ $i - 1 ]['popup_imagetitle'];
					$slide_data[ 'slide' . $i . '_popup_image' ]      = $reorder_arr[ $i - 1 ]['popup_image'];
					$slide_data[ 'slide' . $i . '_popup_background' ] = $reorder_arr[ $i - 1 ]['popup_background'];
					$slide_data[ 'slide' . $i . '_popup_video_id' ]   = $reorder_arr[ $i - 1 ]['popup_video_id'];
					$slide_data[ 'slide' . $i . '_popup_video_type' ] = $reorder_arr[ $i - 1 ]['popup_video_type'];
					$slide_data[ 'slide' . $i . '_popup_html' ]       = $reorder_arr[ $i - 1 ]['popup_html'];
					$slide_data[ 'slide' . $i . '_popup_shortcode' ]  = $reorder_arr[ $i - 1 ]['popup_shortcode'];
					$slide_data[ 'slide' . $i . '_popup_bgcol' ]      = $reorder_arr[ $i - 1 ]['popup_bgcol'];
					$slide_data[ 'slide' . $i . '_popup_width' ]      = $reorder_arr[ $i - 1 ]['popup_width'];
					$slide_data[ 'slide' . $i . '_popup_css_id' ]     = $reorder_arr[ $i - 1 ]['popup_css_id'];
				}
			}

			// GENERATE HTML CODE FOR THE OWL CAROUSEL SLIDER.
			$wrapper_style  = 'background:' . $slide_data['background_color'] . '; ';
			$wrapper_style .= 'border:solid ' . $slide_data['border_width'] . 'px ' . $slide_data['border_color'] . '; ';
			$wrapper_style .= 'border-radius:' . $slide_data['border_radius'] . 'px; ';
			$wrapper_style .= 'padding:' . $slide_data['wrapper_padd_top'] . 'px ';
			$wrapper_style .= $slide_data['wrapper_padd_right'] . 'px ';
			$wrapper_style .= $slide_data['wrapper_padd_bottom'] . 'px ';
			$wrapper_style .= $slide_data['wrapper_padd_left'] . 'px;';
			if ( '1' === $slide_data['showcase_slider'] ) {
				$wrapper_style .= ' overflow:hidden;';
			}
			$output            .= "<div class='" . $slide_data['slide_icons_color'] . "' style='" . esc_attr( $wrapper_style ) . "'>\n";
			$additional_classes = '';
			if ( 'true' === $slide_data['pagination'] ) {
				if ( 'true' === $slide_data['autohide_arrows'] ) {
					$additional_classes = 'owl-pagination-true autohide-arrows';
				} else {
					$additional_classes = 'owl-pagination-true';
				}
			} else {
				if ( 'true' === $slide_data['autohide_arrows'] ) {
					$additional_classes = 'autohide-arrows';
				}
			}
			// hero slider.
			if ( '1' === $slide_data['hero_slider'] ) {
				$additional_classes .= ' sa_hero_slider';
			}
			$slider_style = 'visibility:hidden;';
			// showcase slider.
			if ( '1' === $slide_data['showcase_slider'] ) {
				$left_perc     = ( intval( $slide_data['showcase_width'] ) - 100 ) / 2;
				$slider_style .= ' width:' . $slide_data['showcase_width'] . '%;';
				$slider_style .= ' left:-' . $left_perc . '%;';
				if ( '1' === $slide_data['showcase_tablet'] ) {
					$left_perc_tab       = ( intval( $slide_data['showcase_width_tab'] ) - 100 ) / 2;
					$slider_style       .= ' --widthtab:' . $slide_data['showcase_width_tab'] . '%;';
					$slider_style       .= ' --lefttab:-' . $left_perc_tab . '%;';
					$additional_classes .= ' showcase_tablet';
				} else {
					$additional_classes .= ' showcase_hide_tablet';
				}
				if ( '1' === $slide_data['showcase_mobile'] ) {
					$left_perc_mob       = ( intval( $slide_data['showcase_width_mob'] ) - 100 ) / 2;
					$slider_style       .= ' --widthmob:' . $slide_data['showcase_width_mob'] . '%;';
					$slider_style       .= ' --leftmob:-' . $left_perc_mob . '%;';
					$additional_classes .= ' showcase_mobile';
				} else {
					$additional_classes .= ' showcase_hide_mobile';
				}
			}
			$output .= "<div id='" . esc_attr( $slide_data['css_id'] ) . "' class='owl-carousel sa_owl_theme " . $additional_classes . "' ";
			$output .= "data-slider-id='" . esc_attr( $slide_data['css_id'] ) . "' style='" . $slider_style . "'>\n";
			// INITIALISE VAIRABLES FOR POPUPS.
			$lightbox_function   = 'open_lightbox_gallery_' . $slide_data['css_id'];
			$lightbox_gallery_id = 'lightbox_button_' . $slide_data['css_id'];
			$lightbox_count      = 0;
			for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
				$slide_content = $slide_data[ 'slide' . $i . '_content' ];
				if ( 'full' !== $slide_data['bg_image_size'] ) {
					// use predefined WordPress image size (from 'other settings').
					$slide_image_src = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_image_id' ], $slide_data['bg_image_size'] );
				} else {
					// use "full" WordPress image size.
					$slide_image_src = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_image_id' ], 'full' );
				}
				// USE POPUP IMAGE AS SLIDE BACKGROUND IMAGE (IF THIS OPTION SELECTED).
				if ( 'IMAGE' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					if ( ( 'no' !== $slide_data[ 'slide' . $i . '_popup_background' ] ) && ( '' !== $slide_data[ 'slide' . $i . '_popup_image' ] ) ) {
						$slide_image_src = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_popup_imageid' ], $slide_data[ 'slide' . $i . '_popup_background' ] );
					}
				} elseif ( 'VIDEO' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					if ( 'youtube' === $slide_data[ 'slide' . $i . '_popup_video_type' ] ) {
						if ( '99999999' === $slide_data[ 'slide' . $i . '_image_id' ] ) {
							$slide_image_src    = array();
							$popup_video_id     = $slide_data[ 'slide' . $i . '_popup_video_id' ];
							$slide_image_src[0] = 'https://img.youtube.com/vi/' . $popup_video_id . '/maxresdefault.jpg';
						}
					}
				}
				$slide_image_size   = $slide_data[ 'slide' . $i . '_image_size' ];
				$slide_image_pos    = $slide_data[ 'slide' . $i . '_image_pos' ];
				$slide_image_repeat = $slide_data[ 'slide' . $i . '_image_repeat' ];
				$slide_image_color  = $slide_data[ 'slide' . $i . '_image_color' ];
				$slide_style        = 'padding:' . $slide_data['slide_padding_tb'] . '% ' . $slide_data['slide_padding_lr'] . '%; ';
				$slide_style       .= 'margin:0px ' . $slide_data['slide_margin_lr'] . '%; ';
				if ( ! empty( $slide_image_src[0] ) ) {
					$slide_style .= 'background-image:url("' . $slide_image_src[0] . '"); ';
					$slide_style .= 'background-position:' . $slide_image_pos . '; ';
					$slide_style .= 'background-size:' . $slide_image_size . '; ';
					$slide_style .= 'background-repeat:' . $slide_image_repeat . '; ';
				}
				if ( ! empty( $slide_image_color ) && ( 'rgba(0,0,0,0)' !== $slide_image_color ) ) {
					$slide_style .= 'background-color:' . $slide_image_color . '; ';
				}
				if ( strpos( $slide_data['slide_min_height_perc'], 'px' ) !== false ) {
					$slide_style .= 'min-height:' . $slide_data['slide_min_height_perc'] . '; ';
				}

				// BUILD SLIDE LINK HOVER BUTTON.
				$link_output = '';
				if ( '' !== $slide_data[ 'slide' . $i . '_link_url' ] ) {
					$link_title   = ''; // SET LINK TITLE TO BLANK - 03/01/2022.
					$link_output  = "<a class='sa_slide_link_icon' href='" . $slide_data[ 'slide' . $i . '_link_url' ] . "' ";
					$link_output .= "target='" . $slide_data[ 'slide' . $i . '_link_target' ] . "' ";
					$link_output .= "title='" . $link_title . "' aria-label='" . $link_title . "'></a>";
				}

				// BUILD POPUP HOVER BUTTON.
				$popup_output = '';
				if ( ( 'IMAGE' === $slide_data[ 'slide' . $i . '_popup_type' ] ) && ( '' !== $slide_data[ 'slide' . $i . '_popup_image' ] ) ) {
					$lightbox_count++;
					$popup_output = "<div class='sa_popup_zoom_icon' onClick='" . $lightbox_function . '(' . $lightbox_count . ");'></div>";
				}
				if ( ( 'VIDEO' === $slide_data[ 'slide' . $i . '_popup_type' ] ) && ( '' !== $slide_data[ 'slide' . $i . '_popup_video_id' ] ) ) {
					$lightbox_count++;
					$popup_output = "<div class='sa_popup_video_icon' onClick='" . $lightbox_function . '(' . $lightbox_count . ");'></div>";
				}
				if ( 'HTML' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					$lightbox_count++;
					$popup_css_id = $slide_data[ 'slide' . $i . '_popup_css_id' ];
					$popup_output = "<div class='sa_popup_zoom_icon' onClick='document.getElementById(\"" . $popup_css_id . "\").style.display=\"block\";'></div>";
				}

				// DISPLAY SLIDE OUTPUT.
				$css_id = $slide_data['css_id'] . '_slide' . sprintf( '%02d', $slide_data[ 'slide' . $i . '_num' ] );
				if ( '1' === $slide_data['disable_slide_ids'] ) {
					if ( 'true' === $slide_data['vert_center'] ) {
						$output .= "<div class='" . $css_id . " sa_hover_container sa_vert_center_wrap' style='" . esc_attr( $slide_style ) . "'>";
					} else {
						$output .= "<div class='" . $css_id . " sa_hover_container' style='" . esc_attr( $slide_style ) . "'>";
					}
				} else {
					if ( 'true' === $slide_data['vert_center'] ) {
						$output .= "<div id='" . $css_id . "' class='sa_hover_container sa_vert_center_wrap' style='" . esc_attr( $slide_style ) . "'>";
					} else {
						$output .= "<div id='" . $css_id . "' class='sa_hover_container' style='" . esc_attr( $slide_style ) . "'>";
					}
				}
				if ( ( '' !== $link_output ) || ( '' !== $popup_output ) ) {
					if ( 'Top Left' === $slide_data['slide_icons_location'] ) {
						// icons location - top left.
						$style = 'top:0px; left:0px; margin:0px;';
					} elseif ( 'Top Center' === $slide_data['slide_icons_location'] ) {
						// icons location - top center.
						if ( ( '' !== $link_output ) && ( '' !== $popup_output ) ) {
							$hov_margin_l = '-40px'; } else {
							$hov_margin_l = '-20px'; }
							$style = 'top:0px; left:50%; margin-left:' . $hov_margin_l . ';';
					} elseif ( 'Top Right' === $slide_data['slide_icons_location'] ) {
						// icons location - top right.
						$style = 'top:0px; right:0px; margin:0px;';
					} elseif ( 'Bottom Left' === $slide_data['slide_icons_location'] ) {
						// icons location - bottom left.
						$style = 'bottom:0px; left:0px; margin:0px;';
					} elseif ( 'Bottom Center' === $slide_data['slide_icons_location'] ) {
						// icons location - bottom center.
						if ( ( '' !== $link_output ) && ( '' !== $popup_output ) ) {
							$hov_margin_l = '-40px'; } else {
							$hov_margin_l = '-20px'; }
							$style = 'bottom:0px; left:50%; margin-left:' . $hov_margin_l . ';';
					} elseif ( 'Bottom Right' === $slide_data['slide_icons_location'] ) {
						// icons location - bottom right.
						$style = 'bottom:0px; right:0px; margin:0px;';
					} else {
						// icons location - center center (default).
						if ( ( '' !== $link_output ) && ( '' !== $popup_output ) ) {
							$hov_margin_l = '-40px'; } else {
							$hov_margin_l = '-20px'; }
							$style = 'top:50%; left:50%; margin-top:-20px; margin-left:' . $hov_margin_l . ';';
					}
					// check whether to display a 'full slide link' for this slide.
					$full_slide_link = 0;
					if ( ( ( '' === $link_output ) && ( '' !== $popup_output ) ) ||
						( ( '' !== $link_output ) && ( '' === $popup_output ) ) ) {
						if ( '1' === $slide_data['slide_icons_fullslide'] ) {
							$full_slide_link = 1;
						}
					}
					if ( 1 === $full_slide_link ) {
						// display full slide link.
						$output .= "<div class='sa_hover_fullslide'>";
					} else {
						// display link buttons.
						if ( 'true' === $slide_data['slide_icons_visible'] ) {
							$output .= "<div class='sa_hover_buttons always_visible' style='" . $style . "'>";
						} else {
							$output .= "<div class='sa_hover_buttons' style='" . $style . "'>";
						}
					}
					if ( '' !== $link_output ) {
						$output .= $link_output;
					}
					if ( '' !== $popup_output ) {
						$output .= $popup_output;
					}
					$output .= "</div>\n"; // .sa_hover_buttons
				}
				if ( 'true' === $slide_data['vert_center'] ) {
					// vertically center content within each slide.
					// (we do this by wrapping slide content in a '<div>' wrapper.
					$slide_content = "<div class='sa_vert_center'>" . $slide_content . '</div>';
				}
				$output .= $slide_content . "</div>\n"; // .sa_hover_container
			}
			$output .= "</div>\n"; // .owl-carousel

			// THUMBNAIL PAGINATION.
			if ( '1' === $slide_data['thumbs_active'] ) {
				$thumbs_loc     = $slide_data['thumbs_location'];
				$thumbs_opacity = $slide_data['thumbs_opacity'] / 100;
				// thumbnail container - set style.
				$thumbs_style = ' padding:' . $slide_data['thumbs_padding'] . '%;';
				if ( 'inside_left' === $thumbs_loc ) {
					$thumbs_style .= 'left:' . $slide_data['thumbs_padding'] . '%; width:' . $slide_data['thumbs_width'] . 'px;';
				} elseif ( 'inside_right' === $thumbs_loc ) {
					$thumbs_style .= 'right:' . $slide_data['thumbs_padding'] . '%; width:' . $slide_data['thumbs_width'] . 'px;';
				} elseif ( 'outside_bottom' === $thumbs_loc ) {
					$thumbs_style .= ' padding-bottom:0px;';
				}
				$add_classes = '';
				if ( '0' === $slide_data['thumbs_resp_tablet'] ) {
					$add_classes .= ' sa_thumbs_hide_tablet'; }
				if ( '0' === $slide_data['thumbs_resp_mobile'] ) {
					$add_classes .= ' sa_thumbs_hide_mobile'; }
				$output .= "<div id='" . esc_attr( $slide_data['css_id'] ) . "_thumbs' class='sa_owl_thumbs_wrap sa_thumbs_" . $thumbs_loc . $add_classes . "' style='" . $thumbs_style . "'>";
				$output .= "<div class='owl-thumbs' data-slider-id='" . esc_attr( $slide_data['css_id'] ) . "'>";
				for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
					// get background image for the thumb (slide image background).
					if ( ( 'IMAGE' === $slide_data[ 'slide' . $i . '_popup_type' ] ) &&
						( 'no' !== $slide_data[ 'slide' . $i . '_popup_background' ] ) &&
						( '' !== $slide_data[ 'slide' . $i . '_popup_image' ] ) ) {
						$thumb_image_arr = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_popup_imageid' ], $slide_data[ 'slide' . $i . '_popup_background' ] );
						$thumb_image_src = $thumb_image_arr[0];
					} elseif ( ( 'VIDEO' === $slide_data[ 'slide' . $i . '_popup_type' ] ) &&
								( 'youtube' === $slide_data[ 'slide' . $i . '_popup_video_type' ] ) &&
								( '99999999' === $slide_data[ 'slide' . $i . '_image_id' ] ) ) {
						$thumb_image_src = array();
						$popup_video_id  = $slide_data[ 'slide' . $i . '_popup_video_id' ];
						$thumb_image_src = 'https://img.youtube.com/vi/' . $popup_video_id . '/hqdefault.jpg';
					} elseif ( 0 !== $slide_data[ 'slide' . $i . '_image_id' ] ) {
						$thumb_image_src = wp_get_attachment_image_src( $slide_data[ 'slide' . $i . '_image_id' ], $slide_data['thumbs_image_size'] );
						if ( $thumb_image_src ) {
							$thumb_image_src = $thumb_image_src[0];
						} else {
							$thumb_image_src = SA_PLUGIN_PATH . 'images/image_placeholder.jpg';
						}
					} else {
						// use a placeholder image if slide has no background image.
						$thumb_image_src = SA_PLUGIN_PATH . 'images/image_placeholder.jpg';
					}
					// thumbnail - set style.
					$thumb_style  = 'background-image:url("' . $thumb_image_src . '"); ';
					$thumb_style .= 'width:' . $slide_data['thumbs_width'] . 'px; ';
					$thumb_style .= 'height:' . $slide_data['thumbs_height'] . 'px; ';
					$thumb_style .= 'background-position:' . $slide_data[ 'slide' . $i . '_image_pos' ] . '; ';
					$thumb_style .= 'background-size:' . $slide_data[ 'slide' . $i . '_image_size' ] . '; ';
					$thumb_style .= 'background-repeat:' . $slide_data[ 'slide' . $i . '_image_repeat' ] . '; ';
					$thumb_style .= 'opacity:' . $thumbs_opacity . '; ';
					$thumb_style .= 'border:solid ' . $slide_data['thumbs_border_width'] . 'px transparent';
					$output      .= "<div class='owl-thumb-item' style='" . $thumb_style . "' title='Slide " . $i . "'></div>";
				}
				$output .= '</div>';        // .sa_owl_thumbs
				$output .= "</div>\n";  // .sa_owl_thumbs_wrap
			}

			// SHOWCASE CAROUSEL - NAVIGATION CONTAINER.
			if ( '1' === $slide_data['showcase_slider'] ) {
				if ( 'true' === $slide_data['autohide_arrows'] ) {
					$output .= "<div id='showcase_" . esc_attr( $id ) . "' class='showcase_nav owl-nav autohide_arrows'></div>\n";
				} else {
					$output .= "<div id='showcase_" . esc_attr( $id ) . "' class='showcase_nav owl-nav'></div>\n";
				}
			}

			$output .= "</div>\n"; // .white or .black

			// CREATE A CUSTOM (HIDDEN) DIV FOR EACH 'HTML/SHORTCODE' POPUP.
			for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
				if ( 'HTML' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
					$popup_css_id = $slide_data[ 'slide' . $i . '_popup_css_id' ];
					$popup_bgcol  = $slide_data[ 'slide' . $i . '_popup_bgcol' ];
					$popup_width  = $slide_data[ 'slide' . $i . '_popup_width' ];
					$output      .= "<div id='" . $popup_css_id . "' class='sa_custom_popup' onClick='this.style.display = \"none\";'>\n";
					$output      .= "<div class='sa_popup_close' onClick='document.getElementById(\"" . $popup_css_id . "\").style.display=\"none\";'>X</div>";
					$output      .= "<div class='sa_popup_wrap' style='background:" . $popup_bgcol . '; max-width:' . $popup_width . "px;' onclick='event.stopPropagation();'>\n";
					if ( '1' === $slide_data[ 'slide' . $i . '_popup_shortcode' ] ) {
						$output .= do_shortcode( $slide_data[ 'slide' . $i . '_popup_html' ] );
					} else {
						$output .= $slide_data[ 'slide' . $i . '_popup_html' ];
					}
					$output .= "</div>\n";
					$output .= "</div>\n";
				}
			}

			// ### CREATE POPUPS USING LIGHTGALLERY LIBRARY (lightgalleryjs.com) ###
			if ( $lightbox_count > 0 ) {
				$lightgallery_id = 'lightgallery_' . $slide_data['css_id'];

				$output .= "<div id='" . $lightgallery_id . "' style='display:none !important;'>\n";
				for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
					// LOOP THROUGH EACH SLIDE.
					if ( ( 'IMAGE' === $slide_data[ 'slide' . $i . '_popup_type' ] ) && ( '' !== $slide_data[ 'slide' . $i . '_popup_image' ] ) ) {
						// SLIDE CONTAINS AN IMAGE POPUP.
						$img_url     = $slide_data[ 'slide' . $i . '_popup_image' ];
						$img_title   = $slide_data[ 'slide' . $i . '_popup_imagetitle' ];
						$slide_num   = $i + 1;
						$popup_cssid = $lightgallery_id . '_' . $slide_num;
						if ( '' !== $img_title ) {
							$output .= "<div class='lg_item' id='" . $popup_cssid . "' href='" . $img_url . "' data-sub-html='" . $img_title . "'>";
						} else {
							$output .= "<div class='lg_item' id='" . $popup_cssid . "' href='" . $img_url . "'>";
						}
						$output .= 'slide' . $slide_num . "</div>\n";
					}
					if ( ( 'VIDEO' === $slide_data[ 'slide' . $i . '_popup_type' ] ) && ( '' !== $slide_data[ 'slide' . $i . '_popup_video_id' ] ) ) {
						// SLIDE CONTAINS A VIDEO POPUP.
						$video_id   = $slide_data[ 'slide' . $i . '_popup_video_id' ];
						$video_type = $slide_data[ 'slide' . $i . '_popup_video_type' ];
						if ( 'youtube' === $video_type ) {
							$video_url = 'http://www.youtube.com/watch?v=' . $video_id . '&mute=0';
						} elseif ( 'vimeo' === $video_type ) {
							$video_url = 'http://vimeo.com/' . $video_id . '&muted=false';
						}
						$slide_num   = $i + 1;
						$popup_cssid = $lightgallery_id . '_' . $slide_num;

						$output .= "<div class='lg_item' id='" . $popup_cssid . "' data-lg-size='1280-720' data-src='" . $video_url . "'>\n";
						$output .= 'slide' . $slide_num . "</div>\n";
					}
				}
				$output .= "</div>\n";
			}

			// ### ENQUEUE JQUERY SCRIPT IF IT HAS NOT ALREADY BEEN LOADED ###
			if ( ! wp_script_is( 'jquery', 'done' ) ) {
				wp_enqueue_script( 'jquery' );
			}

			// ### GENERATE JQUERY CODE FOR THE OWL CAROUSEL SLIDER ###
			$items_width1 = intval( $slide_data['items_width1'] );
			$items_width2 = intval( $slide_data['items_width2'] );
			$items_width3 = intval( $slide_data['items_width3'] );
			$items_width4 = intval( $slide_data['items_width4'] );
			$items_width5 = intval( $slide_data['items_width5'] );
			$items_width6 = intval( $slide_data['items_width6'] );
			if ( ( 1 === $items_width1 ) && ( 1 === $items_width2 ) && ( 1 === $items_width3 ) &&
				( 1 === $items_width4 ) && ( 1 === $items_width5 ) && ( 1 === $items_width6 ) ) {
				$single_item = 1;
			} else {
				$single_item = 0;
			}

			$output .= "<script type='text/javascript'>\n";
			if ( '1' === $slide_data['sa_window_onload'] ) {
				$output .= "	document.addEventListener('DOMContentLoaded', function() {\n";
			} else {
				$output .= "	jQuery(document).ready(function() {\n";
			}

			// JQUERY CODE FOR OWN CAROUSEL.
			$output .= "		jQuery('#" . esc_attr( $slide_data['css_id'] ) . "').owlCarousel({\n";
			if ( 1 === $single_item ) {
				$output .= "			items : 1,\n";
				if ( ( 'Fade' === $slide_data['transition'] ) || ( 'fade' === $slide_data['transition'] ) ) {
					$output .= "			animateOut : 'fadeOut',\n";
				} elseif ( ( 'Slide Down' === $slide_data['transition'] ) || ( 'goDown' === $slide_data['transition'] ) ) {
					$output .= "			animateOut : 'slideOutDown',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Zoom In' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'fadeOut',\n";
					$output .= "			animateIn : 'zoomIn',\n";
				} elseif ( 'Zoom Out' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'zoomOut',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Flip Out X' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'flipOutX',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Flip Out Y' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'flipOutY',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Rotate Left' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'rotateOutDownLeft',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Rotate Right' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'rotateOutDownRight',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Bounce Out' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'bounceOut',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				} elseif ( 'Roll Out' === $slide_data['transition'] ) {
					$output .= "			animateOut : 'rollOut',\n";
					$output .= "			animateIn : 'fadeIn',\n";
				}
				$output .= '			smartSpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			} else {
				$output .= "			responsive:{\n";
				$output .= '				0:{ items:' . esc_attr( $slide_data['items_width1'] ) . " },\n";
				$output .= '				480:{ items:' . esc_attr( $slide_data['items_width2'] ) . " },\n";
				$output .= '				768:{ items:' . esc_attr( $slide_data['items_width3'] ) . " },\n";
				$output .= '				980:{ items:' . esc_attr( $slide_data['items_width4'] ) . " },\n";
				$output .= '				1200:{ items:' . esc_attr( $slide_data['items_width5'] ) . " },\n";
				$output .= '				1500:{ items:' . esc_attr( $slide_data['items_width6'] ) . " }\n";
				$output .= "			},\n";
			}
			if ( 0.0 === $slide_data['slide_duration'] ) {
				$output .= "			autoplay : false,\n";
				$output .= "			autoplayHoverPause : false,\n";
			} else {
				$output .= "			autoplay : true,\n";
				$output .= '			autoplayTimeout : ' . esc_attr( $slide_data['slide_duration'] ) . ",\n";
				$output .= '			autoplayHoverPause : ' . esc_attr( $slide_data['stop_hover'] ) . ",\n";
			}
			$output .= '			smartSpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			$output .= '			fluidSpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			$output .= '			autoplaySpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			$output .= '			navSpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			$output .= '			dotsSpeed : ' . esc_attr( $slide_data['slide_transition'] ) . ",\n";
			if ( '1' === $slide_data['dot_per_slide'] ) {
				$output .= "			dotsEach : 1,\n";
			}
			$output .= '			loop : ' . esc_attr( $slide_data['loop_slider'] ) . ",\n";
			$output .= '			nav : ' . esc_attr( $slide_data['nav_arrows'] ) . ",\n";
			$output .= "			navText : ['Previous','Next'],\n";
			if ( '1' === $slide_data['showcase_slider'] ) {
				$output .= "			navContainer : '#showcase_" . esc_attr( $id ) . "',\n";
			}
			$output .= '			dots : ' . esc_attr( $slide_data['pagination'] ) . ",\n";
			$output .= "			responsiveRefreshRate : 200,\n";
			if ( 'page' === $slide_data['slide_by'] ) {
				$output .= "			slideBy : 'page',\n";
			} else {
				$output .= '			slideBy : ' . esc_attr( $slide_data['slide_by'] ) . ",\n";
			}
			$output .= "			mergeFit : true,\n";
			$output .= '			autoHeight : ' . esc_attr( $slide_data['auto_height'] ) . ",\n";
			if ( '1' === $slide_data['lazy_load_images'] ) {
				$output .= "			lazyLoad : true,\n";
				$output .= "			lazyLoadEager: 1,\n";
			}
			if ( '1' === $slide_data['thumbs_active'] ) {
				$output .= "			thumbs : true,\n";
				$output .= "			thumbsPrerendered : true,\n";
			}
			if ( '1' === $slide_data['ulli_containers'] ) {
				$output .= "			stageElement : 'ul',\n";
				$output .= "			itemElement : 'li',\n";
			}
			if ( '1' === $slide_data['rtl_slider'] ) {
				$output .= "			rtl : true,\n";
			}

			if ( 0 !== $slide_data['start_pos'] ) {
				$output .= '			startPosition : ' . $slide_data['start_pos'] . ",\n";
			}
			$output .= '			mouseDrag : ' . esc_attr( $slide_data['mouse_drag'] ) . ",\n";
			$output .= '			touchDrag : ' . esc_attr( $slide_data['touch_drag'] ) . "\n";
			$output .= "		});\n";

			// MAKE SLIDER VISIBLE (AFTER 'WINDOW ONLOAD' OR 'DOCUMENT READY' EVENT).
			$output .= "		jQuery('#" . esc_attr( $slide_data['css_id'] ) . "').css('visibility', 'visible');\n";

			// JAVASCRIPT 'WINDOW RESIZE' EVENT TO SET CSS 'min-height' OF SLIDES WITHIN THIS SLIDER.
			if ( '1' !== $slide_data['hero_slider'] ) {
				$slide_min_height = $slide_data['slide_min_height_perc'];
				if ( strpos( $slide_min_height, 'px' ) !== false ) {
					$slide_min_height = '0';
				}
				if ( ( '' !== $slide_min_height ) && ( '0' !== $slide_min_height ) ) {
					$output .= '		sa_resize_' . esc_attr( $slide_data['css_id'] ) . "();\n";    // initial call of resize function.
					$output .= "		window.addEventListener('resize', sa_resize_" . esc_attr( $slide_data['css_id'] ) . ");\n"; // create resize event.
											// RESIZE EVENT FUNCTION (to set slide CSS 'min-height').
					$output .= '		function sa_resize_' . esc_attr( $slide_data['css_id'] ) . "() {\n";
												// get slide min height setting.
					$output .= "			var min_height = '" . $slide_min_height . "';\n";
												// get window width.
					$output .= "			var win_width = jQuery(window).width();\n";
					$output .= "			var slider_width = jQuery('#" . esc_attr( $slide_data['css_id'] ) . "').width();\n";
												// calculate slide width according to window width & number of slides.
					$output .= "			if (win_width < 480) {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width1'] ) . ";\n";
					$output .= "			} else if (win_width < 768) {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width2'] ) . ";\n";
					$output .= "			} else if (win_width < 980) {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width3'] ) . ";\n";
					$output .= "			} else if (win_width < 1200) {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width4'] ) . ";\n";
					$output .= "			} else if (win_width < 1500) {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width5'] ) . ";\n";
					$output .= "			} else {\n";
					$output .= '				var slide_width = slider_width / ' . esc_attr( $slide_data['items_width6'] ) . ";\n";
					$output .= "			}\n";
					$output .= "			slide_width = Math.round(slide_width);\n";
												// calculate CSS 'min-height' using the captured 'min-height' data settings for this slider.
					$output .= "			var slide_height = '0';\n";
					$output .= "			if (min_height == 'aspect43') {\n";
					$output .= '				slide_height = (slide_width / 4) * 3;';
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			} else if (min_height == 'aspect169') {\n";
					$output .= '				slide_height = (slide_width / 16) * 9;';
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			} else {\n";
					$output .= '				slide_height = (slide_width / 100) * min_height;';
					$output .= "				slide_height = Math.round(slide_height);\n";
					$output .= "			}\n";
												// set the slide 'min-height' css value.
					$output .= "			jQuery('#" . esc_attr( $slide_data['css_id'] ) . " .owl-item .sa_hover_container').css('min-height', slide_height+'px');\n";
					$output .= "		}\n";
				}
			}

			// JAVASCRIPT FOR SHOWCASE CAROUSELS ONLY.
			// DYNAMICALLY SET CLASS NAMES FOR LEFTMOST (FIRST) AND RIGHTMOST (LAST) ACTIVE (DISPLAYED) SLIDES.
			if ( '1' === $slide_data['showcase_slider'] ) {
				$output .= "		set_first_last_active_classes('" . esc_attr( $slide_data['css_id'] ) . "');\n";
				$output .= "		jQuery('#" . esc_attr( $slide_data['css_id'] ) . "').on('translated.owl.carousel resized.owl.carousel', function(event) {\n";
				$output .= "			set_first_last_active_classes('" . esc_attr( $slide_data['css_id'] ) . "');\n";
				$output .= "		});\n";
				$output .= "		function set_first_last_active_classes(css_id) {\n";
				$output .= "			var total = jQuery('#" . esc_attr( $slide_data['css_id'] ) . " .owl-stage .owl-item.active').length;\n";
				$output .= "			jQuery('#" . esc_attr( $slide_data['css_id'] ) . " .owl-stage .owl-item').removeClass('sc_partial');\n";
				$output .= "			jQuery('#" . esc_attr( $slide_data['css_id'] ) . " .owl-stage .owl-item.active').each(function(index){\n";
				$output .= "				if (index === 0) {\n"; // this is the first active slide.
				$output .= "					jQuery(this).addClass('sc_partial');\n";
				$output .= "				}\n";
				$output .= "				if (index === total - 1 && total > 1) {\n"; // this is the last active slide.
				$output .= "					jQuery(this).addClass('sc_partial');\n";
				$output .= "				}\n";
				$output .= "			});\n";
				$output .= "		}\n";
			}

			// JAVASCRIPT FOR 'CLICK TO ADVANCE' OPTION ONLY.
			if ( 'true' === $slide_data['click_advance'] ) {
				if ( ( 'false' === $slide_data['touch_drag'] ) && ( 'false' === $slide_data['mouse_drag'] ) ) {
					$output .= '		var cta_' . $id . " = jQuery('#" . esc_attr( $slide_data['css_id'] ) . "');\n";
					$output .= "		jQuery('#" . esc_attr( $slide_data['css_id'] ) . "').click(function() {\n";
					$output .= '			cta_' . $id . ".trigger('next.owl.carousel');\n";
					$output .= "		});\n";
				}
			}

			// JAVASCRIPT FOR 'MOUSEWHEEL NAVIGATION' OPTION ONLY.
			if ( 'true' === $slide_data['mousewheel'] ) {
					$output .= '		var mw_' . $id . " = jQuery('#" . esc_attr( $slide_data['css_id'] ) . "');\n";
					$output .= '		mw_' . $id . ".on('mousewheel', '.owl-stage', function (e) {\n";
					$output .= "			if (e.deltaY>0) {\n";
					$output .= '				mw_' . $id . ".trigger('next.owl');\n";
					$output .= "			} else {\n";
					$output .= '				mw_' . $id . ".trigger('prev.owl');\n";
					$output .= "			}\n";
					$output .= "			e.preventDefault();\n";
					$output .= "		});\n";
			}

			// JAVASCRIPT FOR 'SLIDE GOTO LINKS".
			$output .= "		var owl_goto = jQuery('#" . esc_attr( $slide_data['css_id'] ) . "');\n";
			for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
				$output .= "		jQuery('." . esc_attr( $slide_data['css_id'] ) . '_goto' . $i . "').click(function(event){\n";
				$output .= "			owl_goto.trigger('to.owl.carousel', " . ( $i - 1 ) . ");\n";
				$output .= "		});\n";
			}

			// ### JQUERY/JAVASCRIPT CODE FOR THUMBNAIL PAGINATION ###
			if ( '1' === $slide_data['thumbs_active'] ) {

				// BORDER WIDTH IS SET - SET BORDER COLOUR TO THE ACTIVE THUMB.
				if ( $slide_data['thumbs_border_width'] > 0 ) {
					// set border colour of the active (first) thumb.
					$output .= "		jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .active').css('border-color', '" . $slide_data['thumbs_border_color'] . "');\n";
					$output .= "		var owl = jQuery('#" . esc_attr( $slide_data['css_id'] ) . "');\n";
					// owl carousel change event - set border colour of the active thumb.
					$output .= "		owl.on('changed.owl.carousel', function(event) {\n";
					$output .= "			jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('border-color', 'transparent');\n";
					$output .= "			jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .active').css('border-color', '" . $slide_data['thumbs_border_color'] . "');\n";
					$output .= "		})\n";
				}

				// RESIZE WINDOW EVENT - RESIZE THUMBS WIDTH & HEIGHT DEPENDING ON WINDOW WIDTH BREAKPOINTS.
				$output .= '		sa_resize_thumbs_' . esc_attr( $slide_data['css_id'] ) . "();\n"; // initial call of resize function.
				$output .= "		window.addEventListener('resize', sa_resize_thumbs_" . esc_attr( $slide_data['css_id'] ) . ");\n"; // create resize event.
				$output .= '		function sa_resize_thumbs_' . esc_attr( $slide_data['css_id'] ) . "() {\n";
				$output .= "			var win_width = jQuery(window).width();\n";
				$output .= '			var tablet_perc = parseFloat(' . $slide_data['thumbs_resp_tablet'] . " / 100);\n";
				$output .= '			var mobile_perc = parseFloat(' . $slide_data['thumbs_resp_mobile'] . " / 100);\n";
				$output .= '			var tablet_width = Math.round(' . $slide_data['thumbs_width'] . " * tablet_perc) + 'px';\n";
				$output .= '			var tablet_height = Math.round(' . $slide_data['thumbs_height'] . " * tablet_perc) + 'px';\n";
				$output .= '			var mobile_width = Math.round(' . $slide_data['thumbs_width'] . " * mobile_perc) + 'px';\n";
				$output .= '			var mobile_height = Math.round(' . $slide_data['thumbs_height'] . " * mobile_perc) + 'px';\n";
				$output .= "			if ((mobile_perc != 0) && (win_width < 768)) {\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('width', mobile_width);\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('height', mobile_height);\n";
				$output .= "			} else if ((tablet_perc != 0) && (win_width < 1000)) {\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('width', tablet_width);\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('height', tablet_height);\n";
				$output .= "			} else {\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('width', '" . $slide_data['thumbs_width'] . "px');\n";
				$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs .owl-thumbs .owl-thumb-item').css('height', '" . $slide_data['thumbs_height'] . "px');\n";
				$output .= "			}\n";
				// THUMBS POSITION 'Inside Left' or 'Inside Right' - RESIZE CONTAINER WIDTH DEPENDING ON WINDOW WIDTH BREAKPOINTS.
				if ( ( 'inside_left' === $thumbs_loc ) || ( 'inside_right' === $thumbs_loc ) ) {
					$output .= "			if ((mobile_perc != 0) && (win_width < 768)) {\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('width', mobile_width);\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('height', mobile_height);\n";
					$output .= "			} else if ((tablet_perc != 0) && (win_width < 1000)) {\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('width', tablet_width);\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('height', tablet_height);\n";
					$output .= "			} else {\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('width', '" . $slide_data['thumbs_width'] . "px');\n";
					$output .= "				jQuery('#" . esc_attr( $slide_data['css_id'] ) . "_thumbs').css('height', '" . $slide_data['thumbs_height'] . "px');\n";
					$output .= "			}\n";
				}
				$output .= "		}\n";
			}

			// CALL THE WINDOW RESIZE EVENT AFTER THE OWL CAROUSEL SLIDER HAS BEEN INITIALIZED.
			$output .= '		var resize_' . $id . " = jQuery('.owl-carousel');\n";
			$output .= '		resize_' . $id . ".on('initialized.owl.carousel', function(e) {\n";
			$output .= "			if (typeof(Event) === 'function') {\n";
			// modern browsers.
			$output .= "				window.dispatchEvent(new Event('resize'));\n";
			$output .= "			} else {\n";
			// for IE and other old browsers (causes deprecation warning on modern browsers).
			$output .= "				var evt = window.document.createEvent('UIEvents');\n";
			$output .= "				evt.initUIEvent('resize', true, false, window, 0);\n";
			$output .= "				window.dispatchEvent(evt);\n";
			$output .= "			}\n";
			$output .= "		});\n";
			$output .= "	});\n";
			$output .= "</script>\n";

			// ### CREATE POPUPS USING LIGHTGALLERY LIBRARY (lightgalleryjs.com) ###
			if ( $lightbox_count > 0 ) {
				$output .= "<script type='text/javascript'>\n";
				if ( '1' === $slide_data['sa_window_onload'] ) {
					$output .= "document.addEventListener('DOMContentLoaded', function() {\n";
				} else {
					$output .= "jQuery(document).ready(function() {\n";
				}
				$output .= "lightGallery(document.getElementById('" . $lightgallery_id . "'), {\n";
				$output .= "	plugins: [lgVideo, lgZoom, lgAutoplay],\n";
				$output .= "	autoplayFirstVideo: true,\n";
				$output .= "	selector: '.lg_item',\n";
				$output .= "	licenseKey: '60F5F1E7-E8034357-B387117F-3E9CE6DA'\n";
				$output .= "});\n";

				// MOVE ALL THE HTML/SHORTCODE POPUPS TO THE BOTTOM OF THE HTML DOM.
				for ( $i = 1; $i <= $slide_data['num_slides']; $i++ ) {
					if ( 'HTML' === $slide_data[ 'slide' . $i . '_popup_type' ] ) {
						$popup_css_id = $slide_data[ 'slide' . $i . '_popup_css_id' ];
						$output      .= "document.body.appendChild(document.getElementById('" . $popup_css_id . "'));\n";
					}
				}
				$output .= "});\n";

				// JAVASCRIPT FUNCTION WHICH OPENS A LIGHTGALLERY POPUP ON A SPECIFIED SLIDE.
				$output .= 'function ' . $lightbox_function . "(slide) {\n";
				$output .= "	slide_num = slide + 1;\n";
				$output .= "	var popup_cssid = '" . $lightgallery_id . "' + '_' + slide_num;\n";
				$output .= "	document.getElementById(popup_cssid).click();\n";
				$output .= "}\n";

				$output .= "</script>\n";
			}
		}
	}
	return $output;
}
