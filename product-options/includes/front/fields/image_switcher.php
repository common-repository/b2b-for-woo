<?php
defined( 'ABSPATH' ) || exit();

$counter = 1;
$args    = array(
	'post_type'   => 'product_add_option',
	'post_status' => 'publish',
	'post_parent' => $field_id,
	'fields'      => 'ids',
	'orderby'     => 'menu_order',
	'order'       => 'ASC',
);

$options          = get_posts( $args );
$total_checkboxes = count($options);
$req_field_value  = 'No';
if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) {
	$req_field_value = 'Yes';
}

foreach ( $options as $option_id ) {
	if (empty($option_id)) {
		continue;
	}
	$option_name     = get_post_meta($option_id, 'ck_option_name', true);
	$price_type      = get_post_meta($option_id, 'ck_option_price_type', true);
	$option_price    = (float) get_post_meta($option_id, 'ck_option_price', true);
	$image           = get_post_meta( $option_id, 'ck_options_image', true );
	$ck_option_price = '';

	if ('free' == $price_type) {

		$ck_option_price = $option_name;

	} elseif ('flat_fixed_fee' == $price_type || 'fixed_fee_based_on_quantity' == $price_type ) {

		$ck_option_price = $option_name . ' (+' . get_woocommerce_currency_symbol() . ' ' . number_format($option_price, 2, '.', '') . ')';

	} elseif ('flat_percentage_fee' == $price_type || 'percentage_fee_based_on_quantity' == $price_type) {

		$ck_option_price = $option_name . ' (+' . number_format($option_price, 2, '.', '') . '%)';
	}
	if (empty($image)) {
		$image =  PRO_OP_URL . 'assets/images/add_image.png';
	}

	?>
	<div class="ck_img_div">

		<img 
			src="<?php echo esc_url($image); ?>" 
			style="width: 100%; height: 54px; object-fit: cover;"
		>
		<input type="radio" 
			name="ck_options_<?php echo esc_attr($field_id); ?>" 
			class="ck_image_swatcher <?php echo esc_attr($front_dep_class); ?>" 
			title="<?php echo esc_attr($ck_option_price); ?>" 
			data-option_name = "<?php echo esc_attr($option_name); ?>" 
			data-option_price="<?php echo intval($option_price); ?>" 
			data-price_type="<?php echo esc_attr($price_type . '_image_swatcher'); ?>" 
			data-dependent_on="<?php echo esc_attr($dependent_fields); ?>"
			data-dependent_val="<?php echo esc_attr($dependent_options); ?>"
			value="<?php echo esc_attr($option_id); ?>"
			id="<?php echo esc_attr($f_field_id); ?>"
			<?php if ('yes' == get_post_meta($field_id, 'ck_req_field', true)) : ?>
				required
			<?php endif ?>
		>

	</div>
	<?php
}
