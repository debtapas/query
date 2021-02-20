<?php
/*
Plugin Name: Custom plugin
Plugin URI: https://www.youtube.com/watch?v=n_3nXW8VzgQ
Description: Just another Custom plugin
Author: Tapas Deb
Author URI: https://www.youtube.com/watch?v=n_3nXW8VzgQ&t=1105s
Text Domain: custom-plugin
Domain Path: /languages/
Version: 1.0
*/

// constants
// echo plugin_dir_path(__FILE__); die;  // use for include php files
// echo plugins_url();      // use for include images, js, css



define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("PLUGIN_URL", plugins_url());
define("PLUGIN_VERSION", "1.0");

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


// add new function =====================
function add_new_function(){
	include_once PLUGIN_DIR_PATH."/views/add-new.php";
}

// all page function =====================
function all_page_function(){
	include_once PLUGIN_DIR_PATH."/views/all-page.php";
}

function custom_plugin_assests(){
	//includes css files ===================
	wp_enqueue_style(
		"cpl_style", //unique name for css file
		PLUGIN_URL."/custom-plugin/assets/css/style.css", // css file path
		'', // if depend any other file
		PLUGIN_VERSION, // plugin version number
		//true, // true -> add this in footer or false-> header
	);

	//includes js files ===================
	wp_enqueue_script(
		"cpl_script", //unique name for css file
		PLUGIN_URL."/custom-plugin/assets/js/script.js", // css file path
		'', // if depend any other file
		PLUGIN_VERSION, // plugin version number
		true // true -> add this in footer or false-> header
	);

	wp_enqueue_script(
		"cpl_script", //unique name for css file
		PLUGIN_URL."/custom-plugin/assets/js/script.js", // css file path
		'', // if depend any other file
		PLUGIN_VERSION, // plugin version number
		true // true -> add this in footer or false-> header
	);



// Include custom javascript in head section ===================	
	wp_localize_script("cpl_script", "ajaxurl", admin_url("admin-ajax.php"));
}
// add_action("init", "custom_plugin_assests");

// if(isset($_REQUEST['action'])){ //it checks the action param is set or not
// 	switch ($_REQUEST['action']) { //if set pass to switch method to match case
// 		case 'custom_plugin_library' :

// 		    add_action("admin_init", "add_custom_plugin_library"); // match case
// 			function add_custom_plugin_library(){ // function attached with the action hook
// 			global $wpdb;
// 			include_once PLUGIN_DIR_PATH."/library/custom-plugin-lib.php"; // ajax hndler file within /library folder
// 			}
// 				break;
// 	}
// }
add_action('wp_ajax_demo_ajax', 'demo_ajax');
function demo_ajax(){
	echo 'test';
}

//table generating code ===================
function custom_pluging_tables(){
	global $wpdb;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	//Table create while plugin active 
	if(count($wpdb->get_var('SHOW TABLES LIKE "wp_custom_plugin"')) == 0){
		$sql_query_to_create_table = "CREATE TABLE `wp_custom_plugin` (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		 `name` varchar(150) DEFAULT NULL,
		 `email` varchar(150) DEFAULT NULL,
		 `phone` varchar(150) DEFAULT NULL,
		 `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
		 PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"; // sql query to create table

		dbDelta($sql_query_to_create_table);
	}
}

register_activation_hook(__FILE__, 'custom_pluging_tables');



//Table remove while plugin deactive ====================
function deactivate_table(){
	//uninstall mysql code
	global $wpdb;
	$wpdb->query("DROP table IF Exists wp_custom_plugin");

	//Step1 : we get the id of post means page
	//Step2 : delete post from table

	$the_post_id = get_option("custom_post_page_id"); // the function that reture option value based on option name
	if(!empty($the_post_id)){
		wp_delete_post($the_post_id, true); // Delete Post based on post_id
	}
}
register_deactivation_hook(__FILE__, "deactivate_table");// Deactivation Hook
// register_uninstall_hook(__FILE__, "deactivate_table"); // it deletes the page when plugin will be deleted from list




// this function use for create page while activete plugin ==================
function create_page(){
	// code for create page
	$page = array();
	$page['post_title'] = "Custom pluging online Web Tutor" ;
	$page['post_content'] = "Learning Platfor for wordpress customiztion for theme." ;
	$page['post_status'] = "publish" ;
	$page['post_slug'] = "custom-plugin-online" ;
	$page['post_type'] = "page" ;

	$post_id = wp_insert_post($page); // it's retur the post ID

	add_option("custom_post_page_id", $post_id); // wp_options table from the name of custom_post_page_id
}
register_activation_hook(__FILE__,"create_page");