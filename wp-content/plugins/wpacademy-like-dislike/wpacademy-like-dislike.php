<?php
	/**
	 * Plugin Name:       wpacademy-like-dislike
	 * Plugin URI:        https://www.youtube.com/watch?v=po4I8Hq4qns
	 * Description:       Handle the basics with this plugin.
	 * Version:           1.0.0
	 * Requires at least: 5.2
	 * Requires PHP:      7.2
	 * Author:            Tapas Deb
	 * Author URI:        https://github.com/debtapas
	 * License:           GPL
	 * Text-domain:		  wpaclike
	 */

	//if this file is called directly, abort
	if (!defined('WPINC')) {
		die;
	}

	if (!defined(('WPAC_PLUGIN_VERSION'))) {
		define('WPAC_PLUGIN_VERSION', '1.0.0');
	}

	if (!defined(('WPAC_PLUGIN_DIR'))) {
		define('WPAC_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
	}

	if (!function_exists('wpac_my_plugin_function')) {
		function wpac_my_plugin_function(){

		}
	}


	// limit excerpt length
function wp_like_filter_exmpl($words){
	return 10;
}
add_filter('excerpt_length', 'wp_like_filter_exmpl');


function wp_like_excerp_more($more){
	$more = '<a href="'.get_the_permalink().'">More</a>';
	return $more;
}
add_filter('excerpt_more', 'wp_like_excerp_more');