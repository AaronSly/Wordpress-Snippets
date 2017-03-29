<?php 

/*Plugin Name: Custom Post Types Widget
Description: This plugin creates a widget to display the various custom post types.
Version: 1.0
Author: Aaron Sly @ Essential E-Commerce Ltd
Author URI: http://www.essentialcommerce.co.uk
License: GPLv2
*/

class Custom_Posts_Widget extends WP_Widget 
{
	function __construct() {
		parent::__construct(
			'custom_posts_widget', // Base ID
			'Custom Posts Widget', // Name
			array('description' => __( 'Displays your latest posts based on Custom Post Type.'))
		);
	}
	
	function widget($args, $instance) { //output
		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$numberOfPosts = $instance['numberOfListings'];
		$post_type = $instance['postTypes'];
		$eventLayout = $instance['eventLayout'];
		echo $before_widget;
		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		$this->getCustomPosts($numberOfPosts,$post_type,$eventLayout);
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
		$instance['postTypes'] = strip_tags($new_instance['postTypes']);
		$instance['eventLayout'] = strip_tags($new_instance['eventLayout']);
		return $instance;
	}	
    
    // widget form creation
	function form($instance) {
		//$post_type = '';
	// Check values
	if( $instance) {
		$title = esc_attr($instance['title']);
		$numberOfPosts = esc_attr($instance['numberOfListings']);
		$post_type = esc_attr($instance['postTypes']);
		$eventLayout = esc_attr($instance['eventLayout']);
	} else {
		$title = '';
		$numberOfPosts = '';
		$post_type = '';
		$eventLayout = '';
	}
		$args = array(		
		'public'   => true,
		'_builtin' => false
		);
		$post_types = get_post_types($args);
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'custom_posts_widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
				
		<p class="post-type">
		<label for="<?php echo $this->get_field_id('postTypes'); ?>"><?php _e('Post Type:', 'custom_posts_widget'); ?></label>		
		<select id="<?php echo $this->get_field_id('postTypes'); ?>"  name="<?php echo $this->get_field_name('postTypes'); ?>">
			<?php foreach($post_types as $new_post_type): ?>
			<option <?php echo $new_post_type == $post_type ? 'selected="selected"' : '';?> value="<?php echo $new_post_type;?>"><?php echo $new_post_type; ?></option>
			<?php endforeach;?>
		</select>
		</p>
		
		<p class="<?php echo $this->get_field_id('eventLayout'); ?>" <?php if ($post_type != 'events') :?>style="display:none;"<?php endif;?>>
		<label for="<?php echo $this->get_field_id('eventLayout'); ?>"><?php _e('Event Layout:', 'custom_posts_widget'); ?></label>		
		<select id="<?php echo $this->get_field_id('eventLayout'); ?>"  name="<?php echo $this->get_field_name('eventLayout'); ?>">			
			<option <?php if($eventLayout == 'text'):?> selected="selected" <?php endif;?> value="text">Text</option>
			<option <?php if($eventLayout == 'image'):?> selected="selected" <?php endif;?> value="image">Image</option>
		</select>
		</p> 
		
		<script>
			<?php $currentPT = $this->get_field_id('postTypes');?>
			var pT = jQuery("#<?php echo $currentPT ?>");
			pT.change(function(){
				var selVal = jQuery('#<?php echo $currentPT ?> option:selected').val();
				var update = jQuery(".<?php echo $this->get_field_id('eventLayout');?>");
				console.log(selVal);
				if (selVal === 'events'){
					jQuery(update).show();
					}
				else {
					jQuery(update).hide();
				}
				});
		</script>
		
		<p>
		<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Number of Posts:', 'custom_posts_widget'); ?></label>		
		<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
			<?php for($x=1;$x<=5;$x++): ?>
			<option <?php echo $x == $numberOfPosts ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
			<?php endfor;?>
		</select>
		</p>		 
	<?php
	}
	
	function getCustomPosts($numberOfPosts, $post_type, $eventLayout) { //html
		global $post;
		
		if($post_type == 'events'){
			$today = date('Ymd');
			$eventArgs=array(
			  'post_type' => array($post_type),
			  'meta_query' => array(
					array(
						'key'		=> 'event_end_date',
						'compare'	=> '>=',
						'value'		=> $today,
					)
				),
			  'post_status' => 'publish',
			  'posts_per_page' => $numberOfPosts,
			  'ignore_sticky_posts'=> 1,						  
			  'meta_key'	=> 'event_start_date',
			  'orderby'	=> 'meta_value_num',
			  'order'		=> 'ASC');

			$eventQuery = null;
			$eventQuery = new WP_Query($eventArgs);?>
			<div class="<?php echo $post_type.'-widget-posts'?>">
			<?php
			if( $eventQuery->have_posts() ) {
				$count= 1;
			  while ($eventQuery->have_posts()) : $eventQuery->the_post();
					$startDate = new DateTime(get_field('event_start_date', false, false));
					$endDate = new DateTime(get_field('event_end_date', false, false));
					$postID = get_the_ID();
					$format = get_post_format($postID);
					$image = get_field('header_image', $postID);?>
			<? if ($eventLayout == 'text'):?>
			<div class="spost clearfix">						
				<div class="entry-c">
					<div class="entry-title">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>
					<ul class="iconlist nobottommargin">
						<li><i class="icon-calendar3"></i> 
						<?php echo $startDate->format('j/m/y');?>
						<?php if ($startDate != $endDate){?> - <?php echo $endDate->format('j/m/y');}?></li>
						<li><i class="icon-time"></i>
						<?php strtotime(the_field('event_start_time'));?>
						<?php if (get_field('event_end_time')) :?>
						<?php if (get_field('event_start_time') != get_field('event_end_time') || get_field('event_end_time') != ''):?>
						 - 
						 <?php the_field('event_end_time');?>
						 <?php endif?>
						<?php endif;?></li>
						<li><i class="icon-map-marker2"></i> <?php the_field('event_location');?></li>
						<?php if(!empty(get_field('cost'))) :?>
						<li><i class="icon-gbp"></i> <?php the_field('cost');?></li>	
						<?php endif;?>									
					</ul>
				</div>
			</div>
			<? else:?>
				<div class="sb-event-img spost">							
					<div class="feature-box center media-box fbox-bg">				
						<?php switch ($format) { 
							case 'gallery': ?>
							<div class="fbox-media">
								<div class="fslider" data-arrows="false" data-lightbox="gallery">
									<div class="flexslider">
										<div class="slider-wrap">
											<?php $galleryImgs = get_field('gallery_images', $postID);?>
											<?php foreach ($galleryImgs as $galImg) { ?>
											<?php $resizedImg = $galImg['sizes']['event-homepage'];?>
											<div class="slide"><a href="<?php the_permalink($postID); ?>"><img class="image_fade" src="<?php echo $resizedImg;?>" alt="<?php echo $galImg['alt'];?>"></a></div>
											<?php  }?>									
										</div>
									</div>
								</div>
							</div>
							<?php break; ?>
							<?php case 'video': ?>
							<div class="fbox-media">
								<?php echo the_field('video',$postID); ?>
							</div>
							<?php break; ?>                    
							<?php case 'audio': ?>
							<div class="fbox-media audio">                    
								<?php echo the_field('audio',$postID); ?>
							</div>
							<?php break; ?>                    
							<?php default: ?>
							<div class="fbox-media">
								<?php $smallImg = $image['sizes']['event-homepage'];?>
								<a href="<?php the_permalink($postID); ?>"><img src="<?php echo $smallImg ;?>" alt="<?php echo $image['alt'];?>" class="image_fade"></a>
							</div>
							<?php break; ?>
							<?php }?>				
						<div class="fbox-desc">
							<a href="<?php the_permalink($postID); ?>"><h3><?php the_title(); ?><span class="subtitle"><?php echo the_field('event_location', $postID); ?>, <?php echo the_field('event_start_date', $postID);?></span></h3></a>
						</div>
					</div>							
				</div>
			<?php endif;?>
			<?php
			  endwhile;
			} else{
				echo '<p style="padding:25px;">No Events found.</p>';
			}?>
			</div>
			<?php 
			wp_reset_query();  // Restore global post data stomped by the_post().
		}
		elseif ($post_type == 'gallery'){			
			$args=array(
			  'post_type' => array($post_type),
			  'post_status' => 'publish',
			  'posts_per_page' => $numberOfPosts,
			  'ignore_sticky_posts'=> 1,						  
			  'order' => 'DESC');

			$query = null;
			$query = new WP_Query($args);?>
			<div class="<?php echo $post_type.'-widget-posts'?>">
			<?php				
			if( $query->have_posts() ) {
			  while ($query->have_posts()) : $query->the_post();
				$postID = get_the_ID();
				$format = get_post_format();
				$galImage = get_field('header_image');
				$lgNewsImg = $galImage['sizes']['news-lg-homepage'];?>

				<div class="spost clearfix">
					<div class="<?php echo $format?>">
						<?php switch ($format) { 
						case 'gallery': ?>
							<div class="fslider" data-arrows="false" data-lightbox="gallery">
							<div class="flexslider">
								<div class="slider-wrap">
									<?php $galleryImgs = get_field('gallery_images');?>
									<?php foreach ($galleryImgs as $galImg) { ?>
									<?php $galImgThumb = $galImg['sizes']['news-lg-homepage'];?>
									<div class="slide"><a href="<?php the_permalink(); ?>"><img class="image_fade" src="<?php echo $galImgThumb;?>" alt="<?php echo $galImg['alt'];?>"></a></div>
									<?php } ?>									
								</div>
							</div>
						</div>
						<?php break; ?>
						<?php case 'video': ?>                    
							<?php echo the_field('video'); ?>					
						<?php break; ?>                    
						<?php case 'audio': ?>                   
							<?php echo the_field('audio'); ?>					
						<?php break; ?>                    
						<?php default: ?>                    	
							<a href="<?php the_permalink(); ?>"><img class="image_fade" src="<?php echo $lgNewsImg?>" alt="<?php echo $newsImage['alt']?>"></a>				
						<?php break; ?>
						<?php }?>
					</div>								
					<div class="entry-c">
						<div class="entry-title">
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						</div>
						<ul class="entry-meta">
							<li><?php the_time(get_option('date_format')); ?></li>
						</ul>
					</div>
				</div>
			<?php
			  endwhile;
			}
			else{
				echo '<p style="padding:25px;">No Gallery Posts found.</p>';
			}?>
			</div>
			<?php 
			wp_reset_query();  // Restore global post data stomped by the_post().			
		}
		else{			
			$args=array(
			  'post_type' => array($post_type),
			  'post_status' => 'publish',
			  'posts_per_page' => $numberOfPosts,
			  'ignore_sticky_posts'=> 1,				  
			  'order' => 'DESC');

			$query = null;
			$query = new WP_Query($args);?>
			<div class="<?php echo $post_type.'-widget-posts'?>">
			<?php
			if( $query->have_posts() ) {
			  while ($query->have_posts()) : $query->the_post();?>

				<div class="spost clearfix">										
					<div class="entry-c">
						<div class="entry-title">
							<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						</div>
						<ul class="entry-meta">
							<li><?php the_time(get_option('date_format')); ?></li>
						</ul>
					</div>
				</div>
			<?php
			  endwhile;
			}
			else{
				echo '<p style="padding:25px;">No Posts found.</p>';
			}?>
			</div>
			<?php 
			wp_reset_query();  // Restore global post data stomped by the_post().			
		}
		
	}//getCustomPosts end
	
} //end class Custom_Posts_Widget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("Custom_Posts_Widget");'));
?>
