<?php

/* Hooks the metabox. */
add_action('admin_init', 'dmb_tmm_add_pro', 1);
function dmb_tmm_add_pro()
{
    add_meta_box(
        'tmm_pro',
        'Upgrade to PRO',
        'dmb_tmm_pro_display', // Below
        'tmm',
        'side',
        'high'
    );
}


/* Displays the metabox. */
function dmb_tmm_pro_display()
{ ?>

<div class="dmb_side_block">
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> <strong>Import/export</strong> members from
        one team to another.
    </div>
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> <strong>Equalizer</strong>: Make the length
        of your member boxes the same.
    </div>
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Add a <strong>second photo</strong> that
        will appear when hovering over the first one.
    </div>
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> <strong>Hide members</strong> from the
        team.
    </div>
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Add a read more <strong>toggle
            box</strong>.
    </div>
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Many more features.
    </div>

</div>

<a class="dmb_button dmb_button_huge dmb_button_green dmb_see_pro" target="_blank"
    href="https://wpdarko.com/items/team-members-pro">
    See all the new features&nbsp;
</a>
<div class="dmb_discount_box_pushr"></div>
<div class="dmb_side_block dmb_discount_box">
    <div class="dmb_side_block_title">
        Discount code
    </div>
    <span style="font-size:14px; color:#75b03a;"><strong>7884661</strong> (10% OFF)</span>
</div>

<?php } ?>