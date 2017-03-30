<?php 
/********************************
*Rename the additional information tab
********************************/
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	global $product;

	if ($product->has_attributes()) {
		$tabs['additional_information']['title'] = __( 'Information' );	// Rename the additional information tab
		return $tabs;
	}
	else {
		return $tabs;
	}
}

/********************************
*Rename tab content titles
********************************/
add_filter( 'woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1 );
function wc_change_product_description_tab_heading( $title ) {
	global $post;	
	return $post->post_title;
}

add_filter('woocommerce_product_additional_information_heading','wc_chamge_product_additional_information_heading',11,1);

function wc_chamge_product_additional_information_heading($title) {
	global $post;	
	return $post->post_title.' Information';
}
?>
