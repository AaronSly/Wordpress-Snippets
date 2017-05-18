/***************************************
* Snippet to display a list of child pages sharing the same parent
* is_subpage() function can be found here:
* https://github.com/AaronSly/Wordpress-Snippets/blob/master/functions-file-stuff/page-parent-child-checks.php
*****************************************
  <?php
  $parentID = is_subpage();
		if($parentID) : ?>
		<div class="widget_links clearfix hidden-xs hidden-sm">
			<ul>
			<?php 						
				$args = array(
				'depth'        => 1,
				'title_li'     => false,
				'sort_column'  => 'menu_order',
				'child_of'     => $parentID
			);
			echo wp_list_pages($args);?>
			</ul>
		</div>
  <?php endif;?>

/***************************************
* Snippet to display a list of child pages sharing the same parent and includes the parent page link
* is_subpage() function can be found here:
* https://github.com/AaronSly/Wordpress-Snippets/blob/master/functions-file-stuff/page-parent-child-checks.php
*****************************************
<?php $parentID = is_subpage();
if($parentID) :
$args   = array( 'child_of' => $parentID, 'sort_column' =>'menu_order', 'depth' => 0, 'title_li' => '' );
?>
<ul class="nav-right">	
	<li><a href="<?php the_permalink($parentID);?>"><?php echo get_the_title($parentID);?></a></li>
	<?php wp_list_pages( $args );?>
</ul>
<?php endif;?>

/***************************************
* Snippet to create a jump to select box in a wordpress page - Ideally used on mobile devices
* is_subpage() function can be found here:
* https://github.com/AaronSly/Wordpress-Snippets/blob/master/functions-file-stuff/page-parent-child-checks.php
*****************************************
<?php $parentID = is_subpage();
	if(!empty($parentID)) : ?>
	
	<div class="jump-to clearfix visible-sm visible-xs">
	<?php $args = array(
			'depth'        => 1,
			'sort_column'  => 'menu_order',
			'child_of'     => $parentID,
			'echo' => 0,
			'selected' => $post->ID,
			'id' => 'jumpto'
		);?>

		<form action="<?php bloginfo('url'); ?>" method="get">
			<label for="jumpto">Jump to: </label>
			<?php
			$select = wp_dropdown_pages($args);

			echo str_replace('<select ', '<select onchange="this.form.submit()" ', $select);
			?>
		</form>			
	</div>
<?php endif;?>
