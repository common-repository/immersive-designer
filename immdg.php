<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Immdg
 *
 * @wordpress-plugin
 * Plugin Name:       Immersive designer
 * Plugin URI:        #
 * Description:       3D tool for WooCommerce which help you to configure and make your product customizable in 3D.
 * Version:           1.0.0
 * Author:            Bluegamediversion
 * Author URI:        bluegamediversion@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       immersive-designer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'includes/functions.php';
define( 'IMMDG_PLUGIN_NAME', 'Immersive Designer' );
define( 'IMMDG_CONFIGURATOR_PAGE_TITLE', 'Immersive Configurator Page' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IMMDG_VERSION', '1.0.0' );
define( 'IMMDG_PLUGIN_PATH', plugins_url( '/', __FILE__ ) );
/**
 * The configuration cpt label
 */
define( 'IMMDG_CONFIG_CPT', 'immersive_config' );
/**
 * post meta key for cpt
 */
define( 'IMMDG_OPTIONS', 'immdg-options' );
/**
 * post meta key for part config
 */
define( 'IMMDG_PART_CONFIG_OPTION', 'immdg-part-configs' );
/**
 * Main config type value for select
 */
define( 'IMMDG_MAIN_CONFIG_TYPE_VALUE', array( 'Color', 'Material', 'Texture' ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-immdg-activator.php
 */
function activate_immdg() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-immdg-activator.php';
	Immdg_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-immdg-deactivator.php
 */
function deactivate_immdg() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-immdg-deactivator.php';
	Immdg_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_immdg' );
register_deactivation_hook( __FILE__, 'deactivate_immdg' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-immdg.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-immdg-config.php';

define( 'IMMDG_SELECTOR', 'immdg_config_selector' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_immdg() {

	$plugin = new Immdg();
	$plugin->run();

}
run_immdg();
