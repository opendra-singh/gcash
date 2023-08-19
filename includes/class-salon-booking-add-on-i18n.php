<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://woodevz.com
 * @since      1.0.0
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/includes
 * @author     Woodevz Technologies <shashwat.srivastava@woodevz.com>
 */
class Salon_Booking_Add_On_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'salon-booking-add-on',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
