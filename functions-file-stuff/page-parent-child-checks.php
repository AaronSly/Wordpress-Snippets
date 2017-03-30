<?php
/******************************************************
 *Check if page is has a parent and return parent id 
 *****************************************************/
function is_subpage() {
    global $post;
    if ( is_page() && $post->post_parent ) {
        return $post->post_parent;
    } else {
        return false;
    }
}
?>
