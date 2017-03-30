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

/***************************************
* Ensure top cart contents update when products are added to the cart via AJAX
**************************************/
function my_header_add_to_cart_fragment( $fragments ) {
	
	$count = WC()->cart->cart_contents_count;
	if ($count > 0) {
 	// Update .cart-contents
    ob_start();
    $count = WC()->cart->cart_contents_count;
     if ( $count > 0 ) :?>
		<span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
	<?php endif;
		
    $fragments['span.cart-contents-count'] = ob_get_clean();
	
	//update .top-cart-items
	$fragments['div.top-cart-items'] = '<div class="top-cart-items">'.get_cart_content().'</div>';
	// update .top-cart-action
	ob_start();?>
		
	<div class="top-cart-action clearfix">
		<span class="fleft top-checkout-price"><?php echo WC()->cart->get_cart_total(); ?></span>
		<a href="<?php echo home_url('/cart/') ?>" class="button button-rounded fright">View Cart</a>
	</div>
	
	<?php
	$fragments['div.top-cart-action'] = ob_get_clean();
	$fragments['span.empty-cart'] = '<span class="empty-cart fright"><a href="'.WC()->cart->get_cart_url().'?empty-cart">Empty Cart</a></span>';
	}
	else
	{
		
	$emptyCartText = '<div class="top-cart-items"><div class="top-cart-item clearfix"><p>There are currently no items in your cart.</p></div></div>';
	$fragments['div.top-cart-items'] = '<div class="top-cart-items">'.$emptyCartText.'</div>';
	$fragments['div.top-cart-action'] = '<div class="top-cart-action clearfix"><div class="buttons center"><a href="'.home_url('/shop/').'" class="button wc-forward center">Start Shopping</a></div></div>';
	}	
	
     return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );

/******************************
*Get all Current Items in cart
*******************************/
function get_cart_content() {
	
	$content = WC()->cart->cart_contents;
	
	$output = '';
	foreach( $content as $item ) {
		// Get the image and your specified image size.
		$image = get_the_post_thumbnail($item['product_id'], 'small_thumb' );
		
		$output .=' <div class="top-cart-item clearfix">
                        <div class="top-cart-item-image">
                            <a href="'. get_the_permalink($item['product_id']) .'">'. $image .'</a>
                        </div>
                        <div class="top-cart-item-desc">
                            <a href="'. get_the_permalink($item['product_id']) .'">'. get_the_title( $item['product_id'] ) .'</a>
                            <span class="top-cart-item-price">£ '. number_format($item['data']->price,2) .'</span>
                            <span class="top-cart-item-quantity">x '. $item['quantity'] .'</span>
                       </div>
                    </div>';
		
	}
	
	return $output;
}
?>
