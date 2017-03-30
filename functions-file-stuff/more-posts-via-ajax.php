/*************************************************************
* LOADS MORE POSTS VIA AJAX UPDATE $out TO REQUIRED OUTPUT CODE
**************************************************************/
<?php 
function more_post_ajax(){
  $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 6;
  $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
	$lastpostMonth = (isset($_POST['lastPostMonth'])) ? $_POST['lastPostMonth'] : '';	
	$lpMonth = DateTime::createFromFormat('d/m/Y', $lastpostMonth )->format('m');
	
	$out = '';
    header("Content-Type: text/html");
	
    $args = array(
        'suppress_filters' => true,
        'post_type' => array('gallery'),
        'posts_per_page' => $ppp,
        'paged'    => $page,
    );

    $loop = new WP_Query($args);

   $lastMonth = $lpMonth;
    if ($loop -> have_posts()) {  while ($loop -> have_posts()) { $loop -> the_post();
		$galImage = get_field('header_image');
		$format = get_post_format();
		$lgNewsImg = $galImage['sizes']['news-lg-homepage'];
											 
		$pmTest = get_the_date('m');
		$postMonth = get_the_date('F Y');
		$galleryImgs = get_field('gallery_images');
		$time = get_the_time(get_option('date_format'));
		$shortDesc = get_field('short_description');
		$title = get_the_title();
		$link = get_permalink();
		$audio = get_field('audio');
		$video = get_field('video');	
																 
		if ($pmTest != $lastMonth) { $out .= '<div class="entry entry-date-section notopmargin"><span>'. $postMonth.'</span></div>';}
																 
		$out.= '<div class="entry clearfix"><div class="entry-timeline"><div class="timeline-divider"></div></div>';
		switch ($format) { 
		case 'gallery':
			$out .= '<div class="entry-image '.$format.'"><div class="portfolio-single-image masonry-thumbs col-4" data-big="3" data-lightbox="gallery">';
				foreach ($galleryImgs as $galImg) { 
					$galImgThumb = $galImg['sizes']['news-lg-homepage'];
					$out.= '<a href="'.$galImg['url'].'" data-lightbox="gallery-item"><img class="image_fade" src="'.$galImgThumb.'" alt="'.$galImg['alt'].'"></a>';
				}
			$out .= '</div></div>';
		break;
		case 'video':                   
			$out .= '<div class="entry-image '.$format.'">'.$video.'</div>';				
		break;                   
		case 'audio':                  
			$out .= '<div class="entry-image '.$format.'">'.$audio.'</div>';					
		break;                    
		default:                   	
			$out.= '<div class="entry-image '.$format.'">'.'<a href="'.the_permalink().'"><img class="image_fade" src="'.$lgNewsImg.'" alt="'.$newsImage['alt'].'"></a></div>';				
		break;
		}
		$out.= '<div class="entry-title"><h2><a href="'.$link.'">'.$title.'</a></h2></div>
		<ul class="entry-meta clearfix">
			<li><i class="icon-calendar3"></i> '.$time.'</li>
		</ul>
		<div class="entry-content">
			<p>'.$shortDesc.'</p>
			<a href="'.$link.'"class="more-link">Read More</a>
		</div>
	</div>';
	$lastMonth = $pmTest;

		};
};
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');
?>

/*******************************************************************
* ALSO ADD THE FOLLOWING TO FUNCTIONS.PHP WITH OTHER ENQUEUED SCRIPTS
* AND ADD AJAX-POSTS.JS TO THEME FOUND HERE:
* https://github.com/AaronSly/Wordpress-Snippets/blob/master/js/ajax-posts.js
********************************************************************/
wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/ajax-posts.js', array ( 'jquery' ), '', true);
wp_localize_script( 'ajax-script', 'ajax_posts', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'noposts' => __('No older posts found', 'ajax-script'), 'post_count' => wp_count_posts( 'POST TYPE GOES HERE' )->publish));
