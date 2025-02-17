<?php

/* Enqueues front scripts. */
add_action( 'wp_enqueue_scripts', 'add_tmm_scripts', 99 );
function add_tmm_scripts() {

  /* Front end CSS. */
  wp_enqueue_style( 'tmm', plugins_url('css/tmm_style.css', __FILE__));
  
}