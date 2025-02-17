<?php

/* Saves metaboxes. */
add_action('save_post', 'dmb_tmm_plan_meta_box_save');
function dmb_tmm_plan_meta_box_save($post_id)
{
    if (
        !isset($_POST['dmb_tmm_meta_box_nonce']) ||
        !wp_verify_nonce(sanitize_key($_POST['dmb_tmm_meta_box_nonce']), 'dmb_tmm_meta_box_nonce')
    )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    if (!isset($_POST['tmm_data_dumps'])) {
        return;
    }

    /* Gets members. */
    $old_team = get_post_meta($post_id, '_tmm_head', true);

    /* Inits new team. */
    $new_team = [];

    /* Settings. */
    $old_team_settings = [];

    $old_team_settings['_tmm_columns'] = get_post_meta($post_id, '_tmm_columns', true);
    $old_team_settings['_tmm_color'] = get_post_meta($post_id, '_tmm_color', true);
    $old_team_settings['_tmm_bio_alignment'] = get_post_meta($post_id, '_tmm_bio_alignment', true);
    $old_team_settings['_tmm_piclink_beh'] = get_post_meta($post_id, '_tmm_piclink_beh', true);
    $old_team_settings['_tmm_original_font'] = get_post_meta($post_id, '_tmm_original_font', true);
    $old_team_settings['_tmm_display_order'] = get_post_meta($post_id, '_tmm_display_order', true);

    $tmm_data_dumps = array_map('wp_kses_post', wp_unslash($_POST['tmm_data_dumps'])); // sanitize array

    $count = count($tmm_data_dumps) - 1;

    for ($i = 0; $i < $count; ++$i) {
        if ($tmm_data_dumps[$i]) {
            /* Data travels using a single field to avoid max_input_vars issue. */
            $member_data = explode(']--[', $tmm_data_dumps[$i]);

            $member_firstname = $member_data[0];
            $member_lastname = $member_data[1];
            $member_job = $member_data[2];
            $member_bio = $member_data[3];

            $member_scl_type1 = $member_data[4];
            $member_scl_title1 = $member_data[5];
            $member_scl_url1 = $member_data[6];

            $member_scl_type2 = $member_data[7];
            $member_scl_title2 = $member_data[8];
            $member_scl_url2 = $member_data[9];

            $member_scl_type3 = $member_data[10];
            $member_scl_title3 = $member_data[11];
            $member_scl_url3 = $member_data[12];

            $member_photo = $member_data[13];
            $member_photo_url = $member_data[14];

            /* Saves the member if at least one of these fields are not empty. */
            if (
                '' != $member_firstname
                || '' != $member_lastname
                || '' != $member_job
                || '' != $member_bio
                || '' != $member_photo
            ) {
                /* Head. */
                (isset($member_firstname) && $member_firstname) ? $new_team[$i]['_tmm_firstname'] = stripslashes(wp_kses_post($member_firstname)) : $new_team[$i]['_tmm_firstname'] = __('Untitled', TMM_TXTDM);
                (isset($member_lastname) && $member_lastname) ? $new_team[$i]['_tmm_lastname'] = stripslashes(wp_kses_post($member_lastname)) : $new_team[$i]['_tmm_lastname'] = '';
                (isset($member_job) && $member_job) ? $new_team[$i]['_tmm_job'] = stripslashes(wp_kses_post($member_job)) : $new_team[$i]['_tmm_job'] = '';
                (isset($member_bio) && $member_bio) ? $new_team[$i]['_tmm_desc'] = wp_kses_post(balanceTags($member_bio)) : $new_team[$i]['_tmm_desc'] = '';
                (isset($member_photo) && $member_photo) ? $new_team[$i]['_tmm_photo'] = stripslashes(strip_tags(sanitize_text_field($member_photo))) : $new_team[$i]['_tmm_photo'] = '';
                (isset($member_photo_url) && $member_photo_url) ? $new_team[$i]['_tmm_photo_url'] = stripslashes(strip_tags(sanitize_url($member_photo_url))) : $new_team[$i]['_tmm_photo_url'] = '';

                (isset($member_scl_type1) && $member_scl_type1) ? $new_team[$i]['_tmm_sc_type1'] = stripslashes(strip_tags(sanitize_text_field($member_scl_type1))) : $new_team[$i]['_tmm_sc_type1'] = '';
                (isset($member_scl_title1) && $member_scl_title1) ? $new_team[$i]['_tmm_sc_title1'] = stripslashes(strip_tags(sanitize_text_field($member_scl_title1))) : $new_team[$i]['_tmm_sc_title1'] = '';
                (isset($member_scl_url1) && $member_scl_url1) ? $new_team[$i]['_tmm_sc_url1'] = stripslashes(strip_tags(sanitize_text_field($member_scl_url1))) : $new_team[$i]['_tmm_sc_url1'] = '';

                (isset($member_scl_type2) && $member_scl_type2) ? $new_team[$i]['_tmm_sc_type2'] = stripslashes(strip_tags(sanitize_text_field($member_scl_type2))) : $new_team[$i]['_tmm_sc_type2'] = '';
                (isset($member_scl_title2) && $member_scl_title2) ? $new_team[$i]['_tmm_sc_title2'] = stripslashes(strip_tags(sanitize_text_field($member_scl_title2))) : $new_team[$i]['_tmm_sc_title2'] = '';
                (isset($member_scl_url2) && $member_scl_url2) ? $new_team[$i]['_tmm_sc_url2'] = stripslashes(strip_tags(sanitize_text_field($member_scl_url2))) : $new_team[$i]['_tmm_sc_url2'] = '';

                (isset($member_scl_type3) && $member_scl_type3) ? $new_team[$i]['_tmm_sc_type3'] = stripslashes(strip_tags(sanitize_text_field($member_scl_type3))) : $new_team[$i]['_tmm_sc_type3'] = '';
                (isset($member_scl_title3) && $member_scl_title3) ? $new_team[$i]['_tmm_sc_title3'] = stripslashes(strip_tags(sanitize_text_field($member_scl_title3))) : $new_team[$i]['_tmm_sc_title3'] = '';
                (isset($member_scl_url3) && $member_scl_url3) ? $new_team[$i]['_tmm_sc_url3'] = stripslashes(strip_tags(sanitize_text_field($member_scl_url3))) : $new_team[$i]['_tmm_sc_url3'] = '';
            }
        }
    }

    /* Settings. */
    (isset($_POST['team_color']) && $_POST['team_color'])
        ? $new_team_settings['_tmm_color'] = stripslashes(strip_tags(sanitize_hex_color(wp_unslash($_POST['team_color']))))
        : $new_team_settings['_tmm_color'] = '';
    (isset($_POST['team_columns']) && $_POST['team_columns'])
        ? $new_team_settings['_tmm_columns'] = stripslashes(strip_tags(sanitize_text_field(wp_unslash($_POST['team_columns']))))
        : $new_team_settings['_tmm_columns'] = '';
    (isset($_POST['team_bio_align']) && $_POST['team_bio_align'])
        ? $new_team_settings['_tmm_bio_alignment'] = stripslashes(strip_tags(sanitize_text_field(wp_unslash($_POST['team_bio_align']))))
        : $new_team_settings['_tmm_bio_alignment'] = '';
    (isset($_POST['team_force_font']) && $_POST['team_force_font'])
        ? $new_team_settings['_tmm_original_font'] = stripslashes(strip_tags(sanitize_text_field(wp_unslash($_POST['team_force_font']))))
        : $new_team_settings['_tmm_original_font'] = '';
    (isset($_POST['team_display_order']) && $_POST['team_display_order'])
        ? $new_team_settings['_tmm_display_order'] = stripslashes(strip_tags(sanitize_text_field(wp_unslash($_POST['team_display_order']))))
        : $new_team_settings['_tmm_display_order '] = '';
    (isset($_POST['team_piclink_beh']) && $_POST['team_piclink_beh'])
        ? $new_team_settings['_tmm_piclink_beh'] = stripslashes(strip_tags(sanitize_text_field(wp_unslash($_POST['team_piclink_beh']))))
        : $new_team_settings['_tmm_piclink_beh'] = '';

    /* Updates plans. */
    if (!empty($new_team) && $new_team != $old_team)
        update_post_meta($post_id, '_tmm_head', $new_team);
    elseif (empty($new_team) && $old_team)
        delete_post_meta($post_id, '_tmm_head', $old_team);

    if (!empty($new_team_settings['_tmm_color']) && $new_team_settings['_tmm_color'] != $old_team_settings['_tmm_color'])
        update_post_meta($post_id, '_tmm_color', $new_team_settings['_tmm_color']);

    if (!empty($new_team_settings['_tmm_columns']) && $new_team_settings['_tmm_columns'] != $old_team_settings['_tmm_columns'])
        update_post_meta($post_id, '_tmm_columns', $new_team_settings['_tmm_columns']);

    if (!empty($new_team_settings['_tmm_bio_alignment']) && $new_team_settings['_tmm_bio_alignment'] != $old_team_settings['_tmm_bio_alignment'])
        update_post_meta($post_id, '_tmm_bio_alignment', $new_team_settings['_tmm_bio_alignment']);

    if (!empty($new_team_settings['_tmm_original_font']) && $new_team_settings['_tmm_original_font'] != $old_team_settings['_tmm_original_font'])
        update_post_meta($post_id, '_tmm_original_font', $new_team_settings['_tmm_original_font']);

    if (!empty($new_team_settings['_tmm_display_order']) && $new_team_settings['_tmm_display_order'] != $old_team_settings['_tmm_display_order'])
        update_post_meta($post_id, '_tmm_display_order', $new_team_settings['_tmm_display_order']);

    if (!empty($new_team_settings['_tmm_piclink_beh']) && $new_team_settings['_tmm_piclink_beh'] != $old_team_settings['_tmm_piclink_beh'])
        update_post_meta($post_id, '_tmm_piclink_beh', $new_team_settings['_tmm_piclink_beh']);
}
