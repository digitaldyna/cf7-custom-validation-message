<?php

/**
 * Fired during plugin activation
 *
 * @link       http://digitaldyna.com
 * @since      1.0.0
 *
 * @package    CF7_CVM
 * @subpackage CF7_CVM/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CF7_CVM
 * @subpackage CF7_CVM/includes
 * @author     Support <support@digitaldyna.com>
 */
class CF7_CVM_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( !in_array( 'contact-form-7/wp-contact-form-7.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			deactivate_plugins( CF7_CVM_BASE_URL );
			//wp_die( __( 'Please install and Activate Contact Form 7.', 'cf7-custom-validation-message' ), 'Plugin dependency check', array( 'back_link' => true ) );
		 	die( __( 'Please install and Activate Contact Form 7.', 'cf7-custom-validation-message' ) );
		}

	}

}
