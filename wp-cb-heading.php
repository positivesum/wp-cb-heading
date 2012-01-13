<?php
/*
Plugin Name: WPCB Overloaded Heading
Plugin URI: 
Description: WP plugin to overload heading module
Version: 0.1
Author: Amit Singh
Author URI: http://amiworks.com
*/
if ( !class_exists( 'wp_cb_oheading' ) ) {
	class wp_cb_oheading {
		/**
		 * Initializes plugin variables and sets up wordpress hooks/actions.
		 *
		 * @return void
		 */
		function __construct( ) {
			$this->pluginDir		= basename(dirname(__FILE__));
			$this->pluginPath		= WP_PLUGIN_DIR . '/' . $this->pluginDir;
			add_action('cfct-modules-loaded',  array(&$this, 'wp_cb_oheading_load'));	
		}

		function wp_cb_oheading_load() {
			require_once($this->pluginPath . "/oheading.php");				
		}			
		
	}
	$wp_cb_oheading = new wp_cb_oheading();	
}
