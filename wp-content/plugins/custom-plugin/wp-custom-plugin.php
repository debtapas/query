<?php
/*
Plugin Name: Custom plugin
Plugin URI: https://www.youtube.com/watch?v=1KGysPdXcTQ
Description: Just another Custom plugin
Author: Tapas Deb
Author URI: https://wordpress.com/
Text Domain: custom-plugin
Domain Path: /languages/
Version: 1.0
*/

// constants
// echo plugin_dir_path(__FILE__);   // use for include php files
// echo plugins_url();      // use for include images, js, css



define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("PLUGIN_URL", plugins_url());

function add_my_custom_menu(){
	add_menu_page(
		"customplugin", // page title
		"Custom Plugin", // menu title
		"manage_options", // admin level
		"custom-plugin1",  // page slug ~~ parent slug
		"add_new_function", // callback function
		"dashicons-tickets", // icon url
		6 // positions
	);

	add_submenu_page(
		"custom-plugin1", //parent slug
		"Add New", // page title
		"Add New", // menu title
		"manage_options", //capability = user_level access
		"custom-plugin1", // menu slug
		"add_new_function" // callback function
	);

	add_submenu_page(
		"custom-plugin1", //parent slug
		"All Pages", // page title
		"All Pages", // menu title
		"manage_options", //capability = user_level access
		"all-pages", // menu slug
		"all_page_function" // callback function
	);

}
add_action('admin_menu', 'add_my_custom_menu');


// add new function
function add_new_function(){
	include_once PLUGIN_DIR_PATH."/views/add-new.php";
}

// all page function
function all_page_function(){
	include_once PLUGIN_DIR_PATH."/views/all-page.php";
}
