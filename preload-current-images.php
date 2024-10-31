<?php
/*
Plugin Name: Preload Current Images
Plugin URI: http://wordpress.org/extend/plugins/preload-current-images/
Description: Preloads all images on the current page, making the images render faster. Works especially well for slides.

Installation:

1) Install WordPress 3.6 or higher

2) Download the following file:

http://downloads.wordpress.org/plugin/preload-current-images.zip

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.3
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

add_shortcode( 'preload_progress_bar', 'preload_progress_bar_shortcode' );

function preload_progress_bar_shortcode($atts) {
	return "<script language='javascript'>
	jQuery(window).load(function() {
		var progressbar = jQuery( '#progressbar' ),
	    progressLabel = jQuery( '.progress-label' );
		".$atts["complete"]."
	});
	jQuery(function() {
	  var progressbar = jQuery( '#progressbar' ),
	    progressLabel = jQuery( '.progress-label' );

	  progressbar.progressbar({
	    value: false,
	    change: function() {
	      progressLabel.text( progressbar.progressbar( 'value' ) + '%' );
	    },
	    complete: function() {
	      ".$atts["complete"]."
	    }
	  });

	  function progress() {
	    var val = progressbar.progressbar( 'value' ) || 0;

	    //progressbar.progressbar( 'value', val + 1 );

	    if (progressbar.progressbar('value') < val) {
	    	val = val + 1;
	    } else {
		    if (jQuery('body').attr('loaded_image_count_on_page')) {
		      progressbar.progressbar( 'value', parseInt((jQuery('body').attr('loaded_image_count_on_page') / jQuery('body').attr('image_count_on_page')) * 100) );  
		    }
	    }
	    progressbar.progressbar( 'value', val + 1 );
	    
	    if ( val < 99 ) {
	      setTimeout( progress, 250 );
	    }
	  }

	  setTimeout( progress, 3000 );
	});
  </script><div id='progressbar'><div class='progress-label'>".$atts["initial_text"]."</div></div>";
}

add_action('wp_head', 'add_preload_current_images_js');
function add_preload_current_images_js() { 
  wp_enqueue_script('jquery');
  wp_register_script( 'my-jquery-preload-images', plugins_url("/js/application.js", __FILE__) );
  wp_enqueue_script('my-jquery-preload-images');
  wp_register_style("my-preload-progress-bar", plugins_url("/css/style.css", __FILE__));
  wp_enqueue_style("my-preload-progress-bar");
}

?>