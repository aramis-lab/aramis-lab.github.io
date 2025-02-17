<?php

/* Defines highlight select options. */
function dmb_tmm_social_links_options()
{
    $options = [
        __('-', TMM_TXTDM) => 'nada',
        __('X (Twitter)', TMM_TXTDM) => 'twitter',
        __('LinkedIn', TMM_TXTDM) => 'linkedin',
        __('YouTube', TMM_TXTDM) => 'youtube',
        __('WhatsApp', TMM_TXTDM) => 'whatsapp',
        __('Messenger', TMM_TXTDM) => 'messenger',
        __('WeChat', TMM_TXTDM) => 'wechat',
        __('Facebook', TMM_TXTDM) => 'facebook',
        __('Pinterest', TMM_TXTDM) => 'pinterest',
        __('VK', TMM_TXTDM) => 'vk',
        __('Instagram', TMM_TXTDM) => 'instagram',
        __('Tumblr', TMM_TXTDM) => 'tumblr',
        __('Research Gate', TMM_TXTDM) => 'researchgate',
        __('Email', TMM_TXTDM) => 'email',
        __('Website', TMM_TXTDM) => 'website',
        __('Phone', TMM_TXTDM) => 'phone',
        __('Other links', TMM_TXTDM) => 'customlink',
        __('Google+ (deprecated)', TMM_TXTDM) => 'googleplus',
    ];

    return $options;
}

/* Hooks the metabox. */
add_action('admin_init', 'dmb_tmm_add_team', 1);
function dmb_tmm_add_team()
{
    add_meta_box(
        'tmm',
        __('Manage your team', TMM_TXTDM),
        'dmb_tmm_team_display', // Below
        'tmm',
        'normal',
        'high'
    );
}

/* Displays the metabox. */
function dmb_tmm_team_display()
{
    global $post;

    /* Gets team data. */
    $team = get_post_meta($post->ID, '_tmm_head', true);

    $fields_to_process = [
        '_tmm_firstname',
        '_tmm_lastname',
        '_tmm_job',
        '_tmm_desc',
        '_tmm_sc_type1', '_tmm_sc_title1', '_tmm_sc_url1',
        '_tmm_sc_type2', '_tmm_sc_title2', '_tmm_sc_url2',
        '_tmm_sc_type3', '_tmm_sc_title3', '_tmm_sc_url3',
        '_tmm_photo',
        '_tmm_photo_url',
    ];

    /* Retrieves select options. */
    $social_links_options = dmb_tmm_social_links_options();

    wp_nonce_field('dmb_tmm_meta_box_nonce', 'dmb_tmm_meta_box_nonce'); ?>

<div id="dmb_preview_team">
    <!-- Closes preview button. -->
    <a class="dmb_button dmb_button_huge dmb_button_gold dmb_preview_team_close" href="#">
        <?php esc_html_e('Close preview', TMM_TXTDM); ?>
    </a>
</div>

<?php if (!class_exists('acf')) { ?>

<div id="dmb_unique_editor">
    <?php wp_editor('', 'dmb_editor', ['editor_height' => '300px']); ?>
    <br />
    <a class="dmb_button dmb_button_huge dmb_button_blue dmb_ue_update" href="#">
        <?php esc_html_e('Update biography', TMM_TXTDM); ?>
    </a>
    <a class="dmb_button dmb_button_huge dmb_ue_cancel" href="#">
        <?php esc_html_e('Cancel', TMM_TXTDM); ?>
    </a>
</div>

<?php } ?>

<!-- Toolbar for member metabox -->
<div class="dmb_toolbar">
    <a class="dmb_button dmb_button_large dmb_expand_rows" href="#"><span
            class="dashicons dashicons-editor-expand"></span>
        <?php esc_html_e('Expand all', TMM_TXTDM); ?></a>
    <a class="dmb_button dmb_button_large dmb_collapse_rows" href="#"><span
            class="dashicons dashicons-editor-contract"></span>
        <?php esc_html_e('Collapse all', TMM_TXTDM); ?></a>
    <a
        class="dmb_show_preview_team dmb_button dmb_button_huge dmb_button_gold"><?php esc_html_e('Instant preview', TMM_TXTDM); ?></a>
    <div class="dmb_clearfix"></div>
</div>

<?php if ($team) {
    /* Loops through rows. */
    foreach ($team as $team_member) {
        /* Retrieves each field for current member. */
        $member = [];
        foreach ($fields_to_process as $field) {
            switch ($field) {
                default:
                    $member[$field] = (isset($team_member[$field])) ? esc_attr($team_member[$field]) : '';
                    break;
            }
        } ?>

<!-- START member -->
<div class="dmb_main">

    <textarea class="dmb_data_dump" name="tmm_data_dumps[]"></textarea>

    <!-- Member handle bar -->
    <div class="dmb_handle">
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_up" href="#" title="Move up"><span
                class="dashicons dashicons-arrow-up-alt2"></span></a>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_down" href="#" title="Move down"><span
                class="dashicons dashicons-arrow-down-alt2"></span></a>
        <div class="dmb_handle_title"></div>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_remove_row_btn" href="#" title="Remove"><span
                class="dashicons dashicons-trash"></span></a>
        <a class="dmb_button dmb_button_large dmb_clone_row" href="#" title="Clone"><span
                class="dashicons dashicons-admin-page"></span><?php esc_html_e('Clone', TMM_TXTDM); ?></a>
        <div class="dmb_clearfix"></div>
    </div>

    <!-- START inner -->
    <div class="dmb_inner">

        <div class="dmb_section_title">
            <?php esc_html_e('Member details', TMM_TXTDM); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('First name', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_highlight_field dmb_firstname_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_firstname']); ?>"
                placeholder="<?php esc_attr_e('e.g. John', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <div class="dmb_field_title">
                <?php esc_html_e('Lastname', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_lastname_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_lastname']); ?>"
                placeholder="<?php esc_html_e('e.g. Doe', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Job/role', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_job_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_job']); ?>"
                placeholder="<?php esc_html_e('e.g. Project manager', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last">

            <?php if (!class_exists('acf')) { ?>

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Edit your member\'s biography by clicking the button below. Once updated, it will show up here.', TMM_TXTDM); ?>">[?]</a>
            </div>

            <div class="dmb_field dmb_description_of_member">
                <?php echo htmlentities($member['_tmm_desc']); ?>
            </div>

            <?php } else { ?>

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', TMM_TXTDM); ?>
            </div>

            <div class="dmb_field dmb_description_of_member_fb" style="display:none !important;">
                <?php echo htmlentities($member['_tmm_desc']); ?>
            </div>
            <textarea id="acf-fallback-bio"><?php echo wp_kses_post($member['_tmm_desc']); ?></textarea>

            <?php } ?>

            <div class="dmb_clearfix"></div>

            <?php if (!class_exists('acf')) { ?>
            <div class="dmb_edit_description_of_member dmb_button dmb_button_large dmb_button_blue">
                <?php esc_html_e('Edit biography', TMM_TXTDM); ?>
            </div>
            <?php } ?>

        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Social links', TMM_TXTDM); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('These links will appear below your members\' biography.', TMM_TXTDM); ?>">[?]</a>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('Link type', TMM_TXTDM); ?>
            </div>
            <select class="dmb_scl_type_select dmb_scl_type1_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>"
                    <?php selected($member['_tmm_sc_type1'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <div class="dmb_field_title">
                <?php esc_html_e('Title attribute', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Optional. This is the HTML <a> tag\'s title attribute.', TMM_TXTDM); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_scl_title1_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_title1']); ?>"
                placeholder="<?php esc_attr_e('e.g. Facebook page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_attr_e('Link URL', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_scl_url1_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_url1']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type2_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>"
                    <?php selected($member['_tmm_sc_type2'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <input class="dmb_field dmb_scl_title2_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_title2']); ?>"
                placeholder="<?php esc_attr_e('e.g. Facebook page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url2_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_url2']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type3_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>"
                    <?php selected($member['_tmm_sc_type3'], $value); ?>>
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <input class="dmb_field dmb_scl_title3_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_title3']); ?>"
                placeholder="<?php esc_attr_e('e.g. Google+ page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url3_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_sc_url3']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://gp.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_tip">
            <span class="dashicons dashicons-yes"></span>
            <?php esc_html_e('Links with the email type open your visitors\' mail client.', TMM_TXTDM); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s email address must be entered in the Link URL field. Title attribute can be left blank.', TMM_TXTDM); ?>">[?]</a>
            <br /><span class="dashicons dashicons-yes"></span>
            <?php esc_html_e('Links with the phone type open your visitors\' default phone app.', TMM_TXTDM); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s phone number must be entered in the Link URL field (e.g. tel:+11234567890). Title attribute can be left blank.', TMM_TXTDM); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Photo', TMM_TXTDM); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">

            <div class="dmb_field_title">
                <?php esc_html_e('Member\'s photo', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('We recommend that all photos are the same sizes.', TMM_TXTDM); ?>">[?]</a>
            </div>

            <div class="dmb_photo_of_member">
                <div class="dmb_field_title dmb_img_data_url"
                    data-img="<?php echo esc_attr_e($member['_tmm_photo']); ?>">
                </div>
                <div class="dmb_upload_img_btn dmb_button dmb_button_large dmb_button_blue">
                    <?php esc_html_e('Upload photo', TMM_TXTDM); ?>
                </div>
            </div>

        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last" style="margin-top:7px;">
            <div class="dmb_field_title">
                <?php esc_html_e('Photo link', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Your visitors will be redirected to this link if they click the member\'s photo.', TMM_TXTDM); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_photo_url_of_member" type="text"
                value="<?php echo wp_kses_post($member['_tmm_photo_url']); ?>"
                placeholder="<?php esc_attr_e('e.g. http://your-site.com/full-member-page/', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <!-- END inner -->
    </div>

    <!-- END row -->
</div>

<?php
    }
} ?>

<!-- START empty member -->
<div class="dmb_main dmb_empty_row" style="display:none;">

    <textarea class="dmb_data_dump" name="tmm_data_dumps[]"></textarea>

    <!-- Member handle bar -->
    <div class="dmb_handle">
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_up" href="#" title="Move up"><span
                class="dashicons dashicons-arrow-up-alt2"></span></a>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_move_row_down" href="#" title="Move down"><span
                class="dashicons dashicons-arrow-down-alt2"></span></a>
        <div class="dmb_handle_title"></div>
        <a class="dmb_button dmb_button_large dmb_button_compact dmb_remove_row_btn" href="#" title="Remove"><span
                class="dashicons dashicons-trash"></span></a>
        <a class="dmb_button dmb_button_large dmb_clone_row" href="#" title="Clone"><span
                class="dashicons dashicons-admin-page"></span><?php esc_html_e('Clone', TMM_TXTDM); ?></a>
        <div class="dmb_clearfix"></div>
    </div>

    <!-- START inner -->
    <div class="dmb_inner">

        <div class="dmb_section_title">
            <?php esc_html_e('Member details', TMM_TXTDM); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('First name', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_highlight_field dmb_firstname_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. John', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 ">
            <div class="dmb_field_title">
                <?php esc_html_e('Lastname', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_lastname_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Doe', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Job/role', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_job_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Project manager', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last">

            <?php if (!class_exists('acf')) { ?>

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Edit your member\'s biography by clicking the button below. Once updated, it will show up here.', TMM_TXTDM); ?>">[?]</a>
            </div>

            <div class="dmb_field dmb_description_of_member"></div>

            <?php } else { ?>

            <div class="dmb_field_title">
                <?php esc_html_e('Description/biography', TMM_TXTDM); ?>
            </div>

            <div class="dmb_field dmb_description_of_member_fb" style="display:none !important;"></div>
            <textarea id="acf-fallback-bio"></textarea>

            <?php } ?>

            <div class="dmb_clearfix"></div>

            <?php if (!class_exists('acf')) { ?>
            <div class="dmb_edit_description_of_member dmb_button dmb_button_large dmb_button_blue">
                <?php esc_html_e('Edit biography', TMM_TXTDM); ?>
            </div>
            <?php } ?>

        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Social links', TMM_TXTDM); ?>
            <a class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('These links will appear below your members\' biography.', TMM_TXTDM); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <div class="dmb_field_title">
                <?php esc_html_e('Link type', TMM_TXTDM); ?>
            </div>

            <select class="dmb_scl_type_select dmb_scl_type1_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <div class="dmb_field_title">
                <?php esc_html_e('Title attribute', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Optional. This is the HTML <a> tag\'s title attribute.', TMM_TXTDM); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_scl_title1_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Facebook page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <div class="dmb_field_title">
                <?php esc_html_e('Link URL', TMM_TXTDM); ?>
            </div>
            <input class="dmb_field dmb_scl_url1_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type2_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <input class="dmb_field dmb_scl_title2_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Facebook page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url2_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://fb.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">
            <select class="dmb_scl_type_select dmb_scl_type3_of_member">
                <?php foreach ($social_links_options as $label => $value) { ?>
                <option value="<?php echo wp_kses_post($value); ?>">
                    <?php echo esc_attr($label); ?>
                </option>
                <?php } ?>
            </select>
        </div>

        <div class="dmb_grid dmb_grid_33">
            <input class="dmb_field dmb_scl_title3_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. Google+ page', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_last">
            <input class="dmb_field dmb_scl_url3_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://gp.com/member-profile', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_tip">
            <span class="dashicons dashicons-yes"></span> Links with the email type open your visitors' mail client. <a
                class="dmb_inline_tip dmb_tooltip_large"
                data-tooltip="<?php esc_attr_e('Your member\'s email address must be entered in the Link URL field. Title attribute can be left blank.', TMM_TXTDM); ?>">[?]</a>
        </div>

        <div class="dmb_clearfix"></div>

        <div class="dmb_section_title">
            <?php esc_html_e('Photo', TMM_TXTDM); ?>
        </div>

        <div class="dmb_grid dmb_grid_33 dmb_grid_first">

            <div class="dmb_field_title">
                <?php esc_html_e('Member\'s photo', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('We recommend that all photos are the same sizes.', TMM_TXTDM); ?>">[?]</a>
            </div>

            <div class="dmb_photo_of_member">
                <div class="dmb_field_title dmb_img_data_url" data-img=""></div>
                <div class="dmb_upload_img_btn dmb_button dmb_button_large dmb_button_blue">
                    <?php esc_html_e('Upload photo', TMM_TXTDM); ?>
                </div>
            </div>

        </div>

        <div class="dmb_grid dmb_grid_100 dmb_grid_first dmb_grid_last" style="margin-top:7px;">
            <div class="dmb_field_title">
                <?php esc_html_e('Photo link', TMM_TXTDM); ?>
                <a class="dmb_inline_tip dmb_tooltip_large"
                    data-tooltip="<?php esc_attr_e('Your visitors will be redirected to this link if they click the member\'s photo.', TMM_TXTDM); ?>">[?]</a>
            </div>
            <input class="dmb_field dmb_photo_url_of_member" type="text" value=""
                placeholder="<?php esc_attr_e('e.g. http://your-site.com/full-member-page/', TMM_TXTDM); ?>" />
        </div>

        <div class="dmb_clearfix" style="margin-bottom:6px"></div>

        <!-- END inner -->
    </div>

    <!-- END empty row -->
</div>

<div class="dmb_clearfix"></div>

<div class="dmb_no_row_notice">
    <?php /* translators: Leave HTML tags */ esc_html_e('Click the Add a member button below to get started.', TMM_TXTDM); ?>
</div>

<!-- Add row button -->
<a class="dmb_button dmb_button_huge dmb_button_green dmb_add_row" href="#">
    <?php esc_html_e('Add a member', TMM_TXTDM); ?>
</a>

<?php }
?>