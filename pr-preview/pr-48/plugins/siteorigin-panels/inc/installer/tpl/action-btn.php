<span
	class="siteorigin-installer-button siteorigin-installer"
	data-type="<?php echo esc_attr( $item['type'] ); ?>"
	data-slug="<?php echo esc_attr( $slug ); ?>"
	data-status="<?php echo esc_attr( $item['status'] ); ?>"
	data-version="<?php echo esc_attr( $item['version'] ); ?>"
	<?php if ( empty( $item['status'] ) ) { ?>
		style="display: none;"
	<?php } ?>
>
	<?php echo esc_html( $text ); ?>
</span>
