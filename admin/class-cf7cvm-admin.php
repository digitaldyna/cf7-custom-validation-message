<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://digitaldyna.com
 * @since      1.0.0
 *
 * @package    CF7_CVM
 * @subpackage CF7_CVM/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CF7_CVM
 * @subpackage CF7_CVM/admin
 * @author     Support <support@digitaldyna.com>
 */
class CF7_CVM_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	function cf7cvm_add_panel( $panels ) {
		$panels['custom-validation'] = array(
			'title'    => __( 'Custom Validation', 'cf7-custom-validation-message' ),
			'callback' => array( $this, 'cf7cvm_panel_callback_fn' ),
		);
		return $panels;
	}
	
	function cf7cvm_panel_callback_fn( $post ) {
		wp_nonce_field( 'wpcf7_custom_validation_security', 'wpcf7_custom_validation_nonce' );
		?>
		<h2><?php _e( 'Custom Validation', 'cf7-custom-validation-message' ); ?></h2>
		
		<fieldset>
			<?php 
			$form_ID     = $post->id; # change the 1538 to your CF7 form ID
			$ContactForm = WPCF7_ContactForm::get_instance( $form_ID );
			$form_fields = $ContactForm->scan_form_tags();
			$arr_values = get_post_meta( $form_ID, '_wpcf7_cv_validation_messages', true );
			$is_active = isset($arr_values['activate']) && $arr_values['activate'] == 1 ? 1 : 0;
			?>
			<table class="form-table"><tbody>
				<tr>
					<th scope="row"><?php _e( 'Activate', 'cf7-custom-validation-message' ); ?></th>
					<td><input type="checkbox" <?php echo isset($is_active) && $is_active == 1 ? 'checked' : ''; ?> name="wpcf7-cv[activate]"></td>
				</tr>
				<tr>
					<th scope="row"><?php _e( 'Your field', 'cf7-custom-validation-message' ); ?></th>
					<td><?php _e( 'Your custom validation message', 'cf7-custom-validation-message' ); ?></td>
				</tr>
				<?php
				foreach ($form_fields as $field) {
					if($field->type === 'submit' || $field->type === 'acceptance'){ continue; }
					$custom_message = isset($arr_values[$field->name]) ? $arr_values[$field->name] : '';
					?>
					<tr>
						<th scope="row">
							<label for="field-<?php echo $field->name; ?>"><?php echo $field->name; ?></label>
						</th>
						<td>
							<input type="text" id="field-<?php echo $field->name; ?>" name="wpcf7-cv[<?php echo $field->name; ?>]" class="regular-text" size="70" value="<?php echo $custom_message; ?>">
						</td>
					</tr>
					<?php
					//confirmation email
					if($field->type === 'email*'){
						$custom_message_confirm = isset($arr_values[$field->name.'_invalid']) ? $arr_values[$field->name.'_invalid'] : '';
					?>
					<tr>
						<th scope="row">
							<label for="field-<?php echo $field->name.'_invalid'; ?>"><?php echo $field->name.'(Wrong Email)'; ?></label>
						</th>
						<td>
							<input type="text" id="field-<?php echo $field->name.'_invalid'; ?>" name="wpcf7-cv[<?php echo $field->name.'_invalid'; ?>]" class="regular-text" size="70" value="<?php echo $custom_message_confirm; ?>">
						</td>
					</tr>
					<?php
					}
				}
			echo '</tbody></table>'; 
		?>
		</fieldset>

		<?php
	}
	
	function cf7cvm_store_messages( $contact_form ) {
		if ( ! isset( $_POST ) || empty( $_POST ) ) {
			return;
		} else {
			if ( ! wp_verify_nonce( sanitize_text_field($_POST['wpcf7_custom_validation_nonce']), 'wpcf7_custom_validation_security' ) ) {
				return;
			}
	
			$form_id = $contact_form->id();
			//$fields  = $this->get_plugin_fields( $form_id );
			if( isset($_POST['wpcf7-cv']) ){
				$fields    = $_POST['wpcf7-cv'];
				$validation_messages = array();
				foreach ( $fields as $name => $value ) {
					if(sanitize_text_field($_POST['wpcf7-cv'][$name]) ==''){ continue; }
					$val = sanitize_text_field($_POST['wpcf7-cv'][$name]);
					if( $name == 'activate' && $val == 'on'){ $val = 1; }
					//if(sanitize_text_field($_POST['wpcf7-cv']['activate']) !='' && sanitize_text_field($_POST['wpcf7-cv']['activate']) =='on'){ $val = 1; }
					//update_post_meta( $form_id, '_wpcf7_cv_' . $name, $val );
					$validation_messages[$name] = $val;
				}
				update_post_meta( $form_id, '_wpcf7_cv_validation_messages', $validation_messages );
			}	
		}
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cf7cvm-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cf7cvm-admin.js', array( 'jquery' ), $this->version, false );

	}

}
