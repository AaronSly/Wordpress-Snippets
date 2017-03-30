<?php 
/**************************************************
*check for empty-cart get param to clear the cart
**************************************************/
add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
  global $woocommerce;
	
	if ( isset( $_GET['empty-cart'] ) ) {
		$woocommerce->cart->empty_cart(); 
	}
}
?>
