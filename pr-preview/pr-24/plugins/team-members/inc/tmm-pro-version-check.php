<?php

/* Checks for PRO version. */
add_action('admin_init', 'tmm_free_pro_check');
function tmm_free_pro_check()
{

  if (is_plugin_active('team-members-pro/tmm_pro.php')) {

    /* Shows admin notice. */
    add_action('admin_notices', 'tmm_free_pro_notice');
    function tmm_free_pro_notice()
    {
      echo '<div class="updated"><p><span class="dashicons dashicons-unlock"></span> Team Members <strong>PRO</strong> was activated and is now taking over the Free version.</p></div>';
    }

    /* Deactivates free version. */
    deactivate_plugins(TMM_PATH . '/tmm.php');
  }
}