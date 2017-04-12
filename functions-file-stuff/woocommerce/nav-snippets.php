<?php 
	// Add data attr to nav items
	add_filter( 'nav_menu_link_attributes', 'add_data_atts', 10, 3 );
	function add_data_atts( $atts, $item, $args ) {
		$dataAtt = str_replace(' ', '-',strtolower($item->title));
		$atts['data-'] = '#'.$dataAtt;
	  return $atts;
	}
?>