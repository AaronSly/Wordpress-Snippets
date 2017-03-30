/********************************
*Limit the number of products per row
********************************/
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

/********************************
*Limit the number of related products
********************************/
function woo_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
}
 
add_filter( 'woocommerce_output_related_products_args', 'as_related_products_args' );
  function as_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	//$args['columns'] = 2; // arranged in 2 columns
	return $args;
}

/********************************
*Limit the number of linked items
********************************/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 15 );

if ( ! function_exists( 'woocommerce_output_upsells' ) ) {
	function woocommerce_output_upsells() {
	    woocommerce_upsell_display( 3,3 ); // Display 3 products in rows of 3
	}
}
