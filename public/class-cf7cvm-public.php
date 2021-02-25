<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://digitaldyna.com
 * @since      1.0.0
 *
 * @package    CF7_CVM
 * @subpackage CF7_CVM/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CF7_CVM
 * @subpackage CF7_CVM/public
 * @author     Support <support@digitaldyna.com>
 */
class CF7_CVM_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	
	public function cf7cv_custom_form_validation($result,$tag) {
		$type = $tag['type'];
		$name = $tag['name'];

		$is_required = $tag->is_required() || 'radio' == $tag->type || 'quiz' == $tag->type;
		$value = isset( $_POST[$name] ) ? (array) $_POST[$name] : array();
		
		$cf7_form_id = sanitize_text_field($_POST['_wpcf7']);
		$arr_values = get_post_meta( $cf7_form_id, '_wpcf7_cv_validation_messages', true );
		$validatation_msg = isset($arr_values[$name]) ? $arr_values[$name] : wpcf7_get_message( $name );
		
		//check if activation
		$is_active = isset($arr_values['activate']) && $arr_values['activate'] == 1 ? 1 : 0;
		if( $is_active == 0 ){
			return $result;
		}
		if($type == 'text*' && sanitize_text_field($_POST[$name]) == '' && $is_required){
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'email*' && sanitize_text_field($_POST[$name]) == ''  && $is_required){   
			$result->invalidate( $name, $validatation_msg );		
		}
		if($type == 'email*' && sanitize_text_field($_POST[$name]) != ''  && $is_required) {
			if(substr(sanitize_text_field($_POST[$name]), 0, 1) == '.' || !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', sanitize_text_field($_POST[$name]))) {  
				$confirm_field = $name.'_invalid';
				$validatation_msg = isset($arr_values[$confirm_field]) ? $arr_values[$confirm_field] : wpcf7_get_message( $name );
				$result->invalidate( $name, $validatation_msg );
			} 
		}

		if($type == 'textarea*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}
		
		if($type == 'tel*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'url*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'checkbox*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'number*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'date*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'select*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'radio' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'file*' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}

		if($type == 'quiz' && sanitize_text_field($_POST[$name]) == '' && $is_required){   
			$result->invalidate( $name, $validatation_msg );
		}
		
		return $result;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7cvm-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7cvm-public.js', array( 'jquery' ), $this->version, false );

	}

}
