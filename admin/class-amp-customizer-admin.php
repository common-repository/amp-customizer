<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://autovisie.nl
 * @since      1.0.0
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/admin
 * @author     Melvr
 */
class Amp_Customizer_Admin {

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
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $options    The options of this plugin.
	 */
	private $options = null;

	/**
	 * The setting fields of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $setting_fields    The setting fields of this plugin.
	 */
	private $setting_fields = null;

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

		$this->get_options();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/amp-customizer-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/amp-customizer-admin.js', array( 'jquery', 'wp-color-picker', 'media-editor' ), $this->version, false );
	}

	/**
	 * Get and set the options
	 *
	 * @return mixed|string|void
	 */
	protected function get_options(){
		if( is_null( $this->options ) ){
			$this->options = get_option( $this->option_name );
		}

		return $this->options;
	}

	/**
	 * Get and set the settings fields
	 *
	 * @return mixed|string|void
	 */
	protected function get_setting_fields(){
		if( is_null( $this->setting_fields ) ){
			$this->setting_fields = array(
				'add_featured_image' => array(
					'title' => __( 'Add Featured Image', $this->text_domain ),
					'type' 	=> 'checkbox',
				),
				'select_logo' => array(
					'title' => __( 'Select a Logo', $this->text_domain ),
					'type' 	=> 'image',
				),
				'header_bg' => array(
					'title' => __( 'Header Background', $this->text_domain ),
					'type' 	=> 'color',
				),
				'title_text_color' => array(
					'title' => __( 'Title Text Color', $this->text_domain ),
					'type' 	=> 'color',
				),
				'content_text_color' => array(
					'title' => __( 'Content Text Color', $this->text_domain ),
					'type' 	=> 'color',
				),
				'content_max_width' => array(
					'title' => __( 'Content Width (without px)', $this->text_domain ),
					'type' 	=> 'text',
				),
			);
		}

		return $this->setting_fields;
	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function amp_add_options_page() {
		add_options_page(
			__( 'AMP Customizer', $this->text_domain ),
			__( 'AMP Customizer', $this->text_domain ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'amp_display_options_page' )
		);
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function amp_display_options_page() {
		include_once plugin_dir_path( dirname( __FILE__ ) ) .  '/admin/partials/amp-customizer-admin-display.php';
	}

	/**
	 * Register the settings
	 */
	public function amp_register_settings(){
		$settings = $this->get_setting_fields();

		/**
		 * Add the title
		 */
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', $this->text_domain ),
			array( $this, 'general_description' ),
			$this->plugin_name
		);

		$this->add_settings( $settings );
	}

	/**
	 * Add the settings by sending a array with 'id' => 'description'
	 *
	 * @param bool $settings
	 * @return bool
	 */
	protected function add_settings( $settings = false ){
		if( !$settings || !is_array($settings) ){
			return false;
		}

		foreach( $settings as $setting => $setting_info ){
			if( empty( $setting ) || empty( $setting_info ) ){
				continue;
			}

			$callback = $setting_info['type'] . '_field_callback';

			add_settings_field(
				$setting,
				__( $setting_info['title'], $this->text_domain ),
				array( $this, $callback ),
				$this->plugin_name,
				$this->option_name . '_general',
				array(
					'setting' => $setting,
					'label_for' => $setting,
				)
			);
		}

		register_setting( $this->plugin_name, $this->option_name, array( $this, 'validate_options' ) );

		return true;
	}

	/**
	 * Render the description for the general section
	 *
	 * @since  1.0.0
	 */
	public function general_description() {
		echo '<p>' . __( 'Customize the output of the Google AMP plugin', $this->text_domain ) . '</p>';
	}

	/**
	 * Render a checkbox input for this plugin
	 *
	 * @param array $args
	 * @return bool
	 */
	public function text_field_callback( array $args ) {
		if( !isset( $args['setting'] ) ){
			return false;
		}

		$this->_show_text_field( $args['setting'] );
	}

	/**
	 * Quick function to echo a text field
	 *
	 * @param $field_name
	 */
	protected function _show_text_field( $field_name ) {
		$name = sprintf( '%s[%s]', $this->option_name, $field_name );
		$val = ( isset( $this->options[$field_name] ) ) ? esc_attr( $this->options[$field_name] ) : '';
		echo '<input type="text" id="' . $name . '" name="' . $name . '" value="' . $val . '" />';
	}

	/**
	 * Render a checkbox input for this plugin
	 *
	 * @param array $args
	 * @return bool
	 */
	public function checkbox_field_callback( array $args ) {
		if( !isset( $args['setting'] ) ){
			return false;
		}

		$this->_show_checkbox_field( $args['setting'] );
	}

	/**
	 * Quick function to echo a checkbox field
	 *
	 * @param $field_name
	 */
	protected function _show_checkbox_field( $field_name ) {
		$name = sprintf( '%s[%s]', $this->option_name, $field_name );
		$val = ( isset( $this->options[$field_name] ) ) ? esc_attr( $this->options[$field_name] ) : '';
		echo '<input type="checkbox" id="' . $name . '" name="' . $name . '" value="1" ' . checked( 1, $val, false ) . '/>';
	}

	/**
	 * Render a color input for this plugin
	 *
	 * @param array $args
	 * @return bool
	 */
	public function color_field_callback( array $args ) {
		if( !isset( $args['setting'] ) ){
			return false;
		}

		$this->_show_color_field( $args['setting'] );
	}

	/**
	 * Quick function to echo a input field using the wp color picker
	 *
	 * @param $field_name
	 */
	protected function _show_color_field( $field_name ) {
		$name = sprintf( '%s[%s]', $this->option_name, $field_name );
		$val = ( isset( $this->options[$field_name] ) ) ? esc_attr( $this->options[$field_name] ) : '';
		echo '<input type="text" name="' . $name . '" value="' . $val . '" class="amp-color-field" />';
	}

	/**
	 * Render a image input for this plugin
	 *
	 * @param array $args
	 * @return bool
	 */
	public function image_field_callback( array $args ) {
		if( !isset( $args['setting'] ) ){
			return false;
		}

		$this->_show_image_field( $args['setting'] );
	}

	/**
	 * Quick function to echo a text field
	 *
	 * @param $field_name
	 */
	protected function _show_image_field( $field_name ) {
		$name = sprintf( '%s[%s]', $this->option_name, $field_name );
		$val = ( isset( $this->options[$field_name] ) ) ? esc_attr( $this->options[$field_name] ) : '';
		$id = wp_rand( 0, 9999 );
		$html = '<div class="image-field-container" id="image-field-' . $id . '">';
			$html .= '<input type="hidden" class="image-field-hidden" id="' . $name . '" name="' . $name . '" value="' . $val . '" />';
			if( !empty( $val ) ){
				$html .= '<img src="' . wp_get_attachment_url( $val ) . '" class="image-preview" style="max-width:75px; margin-right: 5px;" />';
			}
			$html .= '<span data-container="image-field-' . $id . '" class="button amp-media">' . __( 'Select Image', $this->text_domain ) . '</span>';
			$html .= '<span data-container="image-field-' . $id . '" class="button amp-media-remove">' . __( 'Remove Image', $this->text_domain ) . '</span>';
		$html .= '</div>';

		echo $html;
	}

	/**
	 * Function that will validate all fields.
	 */
	public function validate_options( $fields ) {
		$valid_fields = $this->_fields_to_check( $fields );
		return apply_filters( 'validate_options', $valid_fields, $fields);
	}

	/**
	 * Which fields do we need to check and what type are they?
	 *
	 * @return array
	 */
	protected function _fields_to_check( $post_fields ) {
		$valid_fields = array();
		$fields = $this->get_setting_fields();

		foreach( $fields as $setting => $setting_info ){
			if( empty( $setting ) || empty( $setting_info ) ){
				continue;
			}

			$validate_function = sprintf( '_validate_option_value_%s', $setting_info['type'] );
			if( !method_exists( $this, $validate_function ) ){
				$validate_function = '_validate_option_value_other';
			}

			if( $valid_value = $this->$validate_function( $post_fields, $setting ) ){
				$valid_fields[$setting] = $valid_value;
			}
		}

		return $valid_fields;
	}

	/**
	 * Validate option value of type 'other'
	 *
	 * @param $fields
	 * @param $option
	 * @return bool|string
	 */
	protected function _validate_option_value_other( $fields, $option ) {
		if( !isset( $fields[$option] ) ){
			return false;
		}

		return esc_attr( $fields[$option] );
	}

	/**
	 * Validate option value of type 'color'
	 *
	 * @param $fields
	 * @param $option
	 * @return bool|string
	 */
	protected function _validate_option_value_color( $fields, $option ) {
		if( !isset( $fields[$option] ) ){
			return false;
		}

		$value = esc_attr( $fields[$option] );
		if( false === $this->check_color( $value ) ) {
			return $this->options[$option];
		}

		return $value;
	}

	/**
	 * Function that will check if value is a valid HEX color
	 *
	 * @param $value
	 * @return bool
	 */
	public function check_color( $value ) {
		return ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) ? true : false;
	}

}
