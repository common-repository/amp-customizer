<?php

/**
 * The admin post specific functionality of the plugin.
 *
 * @link       https://autovisie.nl
 * @since      1.0.0
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/admin_posts
 */

/**
 * The post-specific functionality of the plugin.
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/admin_posts
 * @author     Melvr
 */
class Amp_Customizer_Admin_Posts {

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
	 * The option name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $option_name
	 */
	private $option_name = 'amp_customizer_options';

	/**
	 * The text domain
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $text-domain
	 */
	private $text_domain = 'amp-customizer';

	/**
	 * The setting_fields
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $setting_fields
	 */
	private $setting_fields = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      string    $plugin_admin    Admin instance.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->get_setting_fields();
	}


	/**
	 * Get and set the settings fields
	 *
	 * @return mixed|string|void
	 */
	protected function get_setting_fields(){
		if( is_null( $this->setting_fields ) ){
			$this->setting_fields = array(
				'hide_in_amp' => array(
					'title' => __( 'Yes', $this->text_domain ),
					'type' 	=> 'checkbox',
				),
			);
		}

		return $this->setting_fields;
	}

	/**
	 * Add a meta box for the 'Do not show AMP' checkbox
	 */
	public function amp_register_meta_boxes() {
		add_meta_box(
			'amp-meta-box',
			__( 'Hide in Google AMP', $this->text_domain ),
			array( $this, 'amp_meta_boxes_callback' ),
			'post',
			'side',
			'default'
		);
	}

	/**
	 * Callback for the meta boxes
	 */
	public function amp_meta_boxes_callback(){
		$this->_add_nonce_field();
		$this->_add_settings( $this->get_setting_fields() );
	}

	/**
	 * Remove the canonical link for posts that have been set to 'hide_im_amp'
	 *
	 * @return bool
	 */
	public function amp_remove_canonical(){
		global $post;
		return ( $this->_hide_in_amp( $post->ID ) ) ? false : true;
	}

	/**
	 * Redirect to the original url when 'hide_in_amp' is set
	 *
	 * @param $amp_template
	 */
	public function amp_redirect_to_original_on_hide( $amp_template ){
		$post_id = $amp_template->get( 'post_id' );
		if( $this->_hide_in_amp( $post_id ) ){
			wp_redirect( get_permalink( $post_id ), 302 );
			exit;
		}
	}

	/**
	 * Hide this post from AMP?
	 *
	 * @param $post_id
	 * @return bool
	 */
	protected function _hide_in_amp( $post_id ){
		$name = sprintf( '%s[%s]', $this->option_name, 'hide_in_amp' );
		return ( get_post_meta ( $post_id, $name, true ) == 1 ) ? true : false;
	}

	/**
	 * Add the settings by sending a array with 'id' => 'description'
	 *
	 * @param bool $settings
	 * @return bool
	 */
	protected function _add_settings( $settings = false ){
		if( !$settings || !is_array($settings) ){
			return false;
		}

		foreach( $settings as $setting => $setting_info ){
			if( empty( $setting ) || empty( $setting_info ) ){
				continue;
			}

			$callback = sprintf( '_show_%s_field', $setting_info['type'] );
			if( method_exists( $this, $callback ) ){
				$this->$callback( $setting, $setting_info['title'] );
			}
		}

		return true;
	}

	/**
	 * Add the nonce field
	 */
	protected function _add_nonce_field(){
		wp_nonce_field( basename( __FILE__ ), $this->option_name . '_nonce', true, true );
	}

	/**
	 * Quick function to echo a checkbox field
	 *
	 * @param $field_name
	 * @param $label
	 */
	protected function _show_checkbox_field( $field_name, $label ) {
		global $post;

		$name = sprintf( '%s[%s]', $this->option_name, $field_name );
		$val = $this->_hide_in_amp( $post->ID );
		echo '<input type="checkbox" id="' . $name . '" name="' . $name . '" value="1" ' . checked( 1, $val, false ) . '/> ';
		echo sprintf( '<label for="%s">%s</label>', $name, $label );
	}

	/**
	 * Save the meta boxes values
	 *
	 * @param $post_id
	 * @param $post
	 * @return mixed $post_id
	 */
	public function amp_save_post_meta( $post_id, $post ) {
		if ( !isset( $_POST[$this->option_name . '_nonce']) || !wp_verify_nonce( $_POST[$this->option_name . '_nonce'], basename(__FILE__) ) ){
			return $post_id;
		}

		if( !current_user_can( "edit_post", $post_id ) ){
			return $post_id;
		}

		if( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE ){
			return $post_id;
		}

		if( $post->post_type != "post" ) {
			return $post_id;
		}

		foreach( $this->get_setting_fields() as $key => $setting ){
			$option_name = sprintf( '%s[%s]', $this->option_name, $key );
			$new_meta_value = ( isset( $_POST[$this->option_name][$key] ) ? sanitize_html_class( $_POST[$this->option_name][$key] ) : '' );
			$meta_value = get_post_meta( $post_id, $option_name, true );

			if ( $new_meta_value && '' == $meta_value ){
				add_post_meta( $post_id, $option_name, $new_meta_value, true );
			}
			elseif ( $new_meta_value && $new_meta_value != $meta_value ){
				update_post_meta( $post_id, $option_name, $new_meta_value );
			}
			elseif ( '' == $new_meta_value && $meta_value ){
				delete_post_meta( $post_id, $option_name, $meta_value );
			}
		}

		return $post_id;
	}

}
