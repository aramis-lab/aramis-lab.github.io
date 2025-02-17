<?php

/* Hooks the metabox. */
add_action('admin_init', 'dmb_tmm_add_help', 1);
function dmb_tmm_add_help()
{
	add_meta_box(
		'tmm_help',
		'Shortcode',
		'dmb_tmm_help_display', // Below
		'tmm',
		'side',
		'high'
	);
}


/* Displays the metabox. */
function dmb_tmm_help_display()
{ ?>

<div class="dmb_side_block">
    <p>
        <?php
			global $post;
			$slug = '';
			$slug = $post->post_name; ?>

        <?php if ($slug != '') { ?>
        <span
            style="display:inline-block;border:solid 2px lightgray; background:white; padding:0 8px; font-size:13px; line-height:25px; vertical-align:middle;">[tmm
            name="<?php echo esc_attr($slug); ?>"]</span>
        <?php } else { ?>
        <span style='display:inline-block;color:#849d3a'>
            <?php /* translators: Leave HTML tags */ esc_attr_e("Publish your team before you can see your shortcode.", TMM_TXTDM); ?>
        </span>
        <?php } ?>
    </p>
    <p>
        <?php /* translators: Leave HTML tags */ esc_attr_e('To display your team on your site, copy-paste the shortcode above in your post/page.', TMM_TXTDM) ?>
    </p>
</div>

<div class="dmb_side_block">
    <div class="dmb_help_title">
        Get support
    </div>
    <a target="_blank" href="https://wpdarko.com/support/submit-a-request/">Submit a ticket</a><br />
    <a target="_blank" href="https://wpdarko.com/support">View documentation</a>
</div>

<?php } ?>