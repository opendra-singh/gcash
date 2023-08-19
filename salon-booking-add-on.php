<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://woodevz.com
 * @since             1.0.0
 * @package           Salon_Booking_Add_On
 *
 * @wordpress-plugin
 * Plugin Name:       Salon Booking Add On
 * Plugin URI:        https://woodevz.com
 * Description:       This plugin add a custom option in salon booking plugin
 * Version:           1.0.0
 * Author:            Woodevz Technologies
 * Author URI:        https://woodevz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       salon-booking-add-on
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SALON_BOOKING_ADD_ON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-salon-booking-add-on-activator.php
 */
function activate_salon_booking_add_on() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-salon-booking-add-on-activator.php';
	Salon_Booking_Add_On_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-salon-booking-add-on-deactivator.php
 */
function deactivate_salon_booking_add_on() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-salon-booking-add-on-deactivator.php';
	Salon_Booking_Add_On_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_salon_booking_add_on' );
register_deactivation_hook( __FILE__, 'deactivate_salon_booking_add_on' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-salon-booking-add-on.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_salon_booking_add_on() {

	$plugin = new Salon_Booking_Add_On();
	$plugin->run();

}
run_salon_booking_add_on();
