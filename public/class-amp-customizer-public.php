<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://autovisie.nl
 * @since      1.0.0
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Amp_Customizer
 * @subpackage Amp_Customizer/public
 * @author     Melvr
 */
class Amp_Customizer_Public {

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
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $options    The options of this plugin.
	 */
	private $options = null;

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
	 * Get a option value if it exists
	 *
	 * @param $option
	 * @return bool|string|void
	 */
	public function av_get_option_value( $option ){
		$options = $this->get_options();

		if( !isset( $options[$option] ) ){
			return false;
		}

		return esc_attr( $options[$option] );
	}

	/**
	 * Add custom css styles
	 */
	public function custom_css_styles(){
		include_once plugin_dir_path( dirname( __FILE__ ) ) .  '/public/partials/amp-customizer-public-css.php';
	}

	public function check_settings_callbacks(){
		$settings = $this->get_options();
		if( !$settings ){
			return false;
		}

		foreach( $settings as $setting => $state ){
			if( empty( $setting ) || empty( $state ) ){
				continue;
			}

			$callback = $setting . '_callback';
			if( !method_exists( $this, $callback ) ){
				continue;
			}

			$this->$callback( $state );
		}
	}

	/**
	 * Should we add the featured image?
	 *
	 * @param $state
	 */
	protected function add_featured_image_callback( $state ){
		if( $state == 1 ){
			add_filter( 'the_content', array( $this, 'add_featured_image' ) );
		}
	}

	/**
	 * Add the featured image
	 *
	 * @param $content
	 * @return string
	 */
	public function add_featured_image( $content ) {
		if ( has_post_thumbnail() ) {
			$image = sprintf( '<p class="amp-featured-image">%s</p>', get_the_post_thumbnail() );
			$content = $image . $content;
		}

		return $content;
	}

	/**
	 * Change the content max width?
	 *
	 * @param $state
	 */
	public function content_max_width_callback( $state ){
		if( !empty($state) && $state > 0 ){
			add_filter( 'amp_content_max_width', array( $this, 'change_content_max_width' ) );
		}
	}

	/**
	 * Change the content max width
	 *
	 * @param $content_max_width
	 * @return mixed
	 */
	public function change_content_max_width( $content_max_width ){
		$options = $this->get_options();
		if( isset( $options['content_max_width'] ) ){
			$content_max_width = $options['content_max_width'];
		}

		return $content_max_width;
	}

}
