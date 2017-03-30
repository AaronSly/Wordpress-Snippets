/***************************************
*Snippet to display a list of child pages sharing the same parent
*is_subpage() function can be found here:
*https://github.com/AaronSly/Wordpress-Snippets/Wordpress-Snippets/functions-file-stuff/page-parent-child-checks.php
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
