/********************************************************
* AJAX REQUEST TO LOAD MORE POST ORIGNIALLY WRITTEN FOR MENPHYS.ORG.UK
* COPY AND CHANGE AS NEEDED
********************************************************/
var $ = jQuery.noConflict();
$( document ).ready(function() {
var ppp = 6; // Post per page
var pageNumber = 1;
	
function load_posts(){
	var lastPostMonth = $('.post-timeline.grid-2 .entry:last ul.entry-meta li').text().trim();	
    pageNumber++;
    var str = '&pageNumber=' + pageNumber + '&ppp=' + ppp + '&lastPostMonth=' + lastPostMonth + '&action=more_post_ajax';	
    $.ajax({
        type: "POST",
        dataType: "html",
        url: ajax_posts.ajaxurl,
        data: str,
        success: function(data){
            var $data = $(data);
            if($data.length){
                $("#posts").append($data).isotope('appended', $data);
				var t = setTimeout( function(){ $("#posts").isotope('layout'); }, 1500 );
				SEMICOLON.initialize.resizeVideos();
				SEMICOLON.widget.loadFlexSlider();
				SEMICOLON.widget.masonryThumbs();
				SEMICOLON.initialize.lightbox();
				var t = setTimeout( function(){
					SEMICOLON.initialize.blogTimelineEntries();
				}, 3000 );
				
				var t = setTimeout( function(){
				SEMICOLON.initialize.blogTimelineEntries();
				}, 2500 );

				$(window).resize(function() {
					$container.isotope('layout');
					var t = setTimeout( function(){
						SEMICOLON.initialize.blogTimelineEntries();
					}, 2500 );
				});				
                $("#more_posts").attr("disabled",false);
            } else{
                $("#more_posts").attr("disabled",true);
            }
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
        }
    });
    return false;		
}

function all_posts_loaded() {
	var $numPosts = ajax_posts.post_count;
	var $numEntries = $('.post-timeline.grid-2').find('.entry').length;
	var $numDateEntries = $('.post-timeline.grid-2').find('.entry.entry-date-section').length;
	var $postsOnPage = $numEntries - $numDateEntries;	
	
	if ($numPosts == $postsOnPage) {
		$('#more_posts').text("That's All Folks")
	}	
}

$("#more_posts").on("click",function(){ // When btn is pressed.
    $("#more_posts").attr("disabled",true); // Disable the button, temp.
    load_posts(); 
	setTimeout(function() {all_posts_loaded()},2500);	
});	

});
