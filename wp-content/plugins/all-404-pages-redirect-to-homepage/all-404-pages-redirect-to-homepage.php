<?php

/*

Plugin Name: All 404 Pages Redirect to Homepage

Description: a plugin to redirect all 404 pages to home page or any custom page

Author: Geek Code Lab

Version: 1.2

Author URI: https://geekcodelab.com/

*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

add_action('admin_menu', 'admin_menu_404r');

add_action('wp', 'redirect_404r');

add_action( 'admin_enqueue_scripts', 'enqueue_styles_scripts_404r' );

function prthgk_plugin_add_settings_link( $links ) { 
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support' ) . '</a>'; 
	array_unshift( $links, $support_link );

	$settings_link = '<a href="options-general.php?page=all-404-redirect-option">' . __( 'Settings' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'prthgk_plugin_add_settings_link');

register_activation_hook( __FILE__ , 'plugin_active_404r' );

function plugin_active_404r()

{

	  $redirect_to= get_redirect_to_404r();
	 $status= get_status_404r();

	 if(empty($redirect_to))

	 {

		 update_option('redirect_to_404r',home_url());

	 }

	 if(empty($status))

	 { 

		update_option('status_404r',0);

	 }

}



function redirect_404r()

{

	if(is_404()) 

	{

	 	

        $redirect_to= get_redirect_to_404r();

        $status= get_status_404r();

	    $link=current_link_404r();

	    if($link == $redirect_to)

	    {

	        echo "<b>All 404 Redirect to Homepage</b> has detected that the target URL is invalid, this will cause an infinite loop redirection, please go to the plugin settings and correct the traget link! ";

	        exit(); 

	    }

	    

	 	if($status=='1' & $redirect_to!=''){

		 	header ('HTTP/1.1 301 Moved Permanently');

			header ("Location: " . $redirect_to);

			exit(); 

		}

	}

}



//---------------------------------------------------------------



function admin_menu_404r() {

	add_options_page('All 404 Redirect to Homepage', 'All 404 Redirect to Homepage', 'manage_options', 'all-404-redirect-option', 'options_menu_404r'  );

}

//---------------------------------------------------------------//

function options_menu_404r() {

	
	if (!current_user_can('manage_options'))  {

			wp_die( __('You do not have sufficient permissions to access this page.') );

		}

		
      include( plugin_dir_path( __FILE__ ) . 'options.php' );

	

}

//---------------------------------------------------------------//

function enqueue_styles_scripts_404r()

{

    if( is_admin() ) {              

        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/style.css";               

        wp_enqueue_style( 'main-404-css', $css );

    }

}