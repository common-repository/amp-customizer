<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              http://www.autovisie.nl
 * @since             1.0.0
 * @package           Amp_Customizer
 *
 * @wordpress-plugin
 * Plugin Name:       AMP Customizer
 * Plugin URI:        http://www.autovisie.nl
 * Description:       A plugin to customize the output of the Google AMP plugin by automattic (https://wordpress.org/plugins/amp/).
 * Version:           1.0.1
 * Author:            Melvr
 * Author URI:        http://www.autovisie.nl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       amp-customizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-amp-customizer-activator.php
 */
function activate_amp_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amp-customizer-activator.php';
	Amp_Customizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-amp-customizer-deactivator.php
 */
function deactivate_amp_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-amp-customizer-deactivator.php';
	Amp_Customizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_amp_customizer' );
register_deactivation_hook( __FILE__, 'deactivate_amp_customizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-amp-customizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_amp_customizer() {

	$plugin = new Amp_Customizer();
	$plugin->run();

}
run_amp_customizer();
