<?php
// code will goes here
add_action( 'woocommerce_thankyou', function ($order_id) {
    $order = wc_get_order( $order_id );
    $in_order = false;
    $url = 'https://www.whooking.com/gracias/';
    $products = array(18200);

	foreach ($order->get_items() as $product) {
		if (in_array($product->get_product_id(), $products)) {
			$in_order = true;
		} break;
	}
	if ( ! $order->has_status( 'failed' )) {
		if ($in_order) {
			wp_safe_redirect($url );
		exit;
		}
	}
});
?>