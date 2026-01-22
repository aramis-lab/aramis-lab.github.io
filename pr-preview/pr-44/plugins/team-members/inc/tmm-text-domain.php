<?php 

/* Loads plugin's text domain. */
add_action( 'plugins_loaded', 'tmm_load_plugin_textdomain' );
function tmm_load_plugin_textdomain() {
  load_plugin_textdomain( TMM_TXTDM, FALSE, TMM_PATH . 'lang/' );
}