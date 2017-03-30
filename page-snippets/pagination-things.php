	/***************************************************************************************
  * ADDS POST PAGNATION THAT INCLUDES PAGE NUMBERS, NEXT, PREVIOUS, FIRST AND LAST BUTTONS
  ***************************************************************************************/
  <?php
			$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
			$per_page = 5;
			$args=array(
			  'post_type' => 'sales',
			  'post_status' => 'publish',
			  'posts_per_page' => $per_page,
			  'paged' => $paged,
			  'order' => 'DESC');

			$my_query = null;
			$my_query = new WP_Query($args);?>
	
			<div class="Pager"> 
			<?php                    
				$big = 999999999; // need an unlikely intege
				$max = intval( $my_query->max_num_pages );
				if(get_query_var('paged') > 1) {
				echo '<a href="'.esc_url( get_pagenum_link( 1 ) ).'" class="first-page">First Page</a>';
				}
				echo paginate_links( array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $max,
					'prev_text' => __('Back'),
					'next_text' => __('Next'),
					
				) );
				if (get_query_var('paged') < $max) {
				echo '<a href="'.esc_url( get_pagenum_link( $max ) ).'" class="last-page">Last Page</a>';
				}
			?>
	  		</div> 
