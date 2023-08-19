<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://woodevz.com
 * @since      1.0.0
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/includes
 * @author     Woodevz Technologies <shashwat.srivastava@woodevz.com>
 */
class Salon_Booking_Add_On {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Salon_Booking_Add_On_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SALON_BOOKING_ADD_ON_VERSION' ) ) {
			$this->version = SALON_BOOKING_ADD_ON_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'salon-booking-add-on';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Salon_Booking_Add_On_Loader. Orchestrates the hooks of the plugin.
	 * - Salon_Booking_Add_On_i18n. Defines internationalization functionality.
	 * - Salon_Booking_Add_On_Admin. Defines all hooks for the admin area.
	 * - Salon_Booking_Add_On_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salon-booking-add-on-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-salon-booking-add-on-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-salon-booking-add-on-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-salon-booking-add-on-public.php';

		$this->loader = new Salon_Booking_Add_On_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Salon_Booking_Add_On_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Salon_Booking_Add_On_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Salon_Booking_Add_On_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'sln.template.service.metabox', $plugin_admin, 'sln_gcash_upload_qr' );
		
		$this->loader->add_action( 'wp_ajax_set_sln_payment_methods', $plugin_admin, 'set_sln_payment_methods' );
		$this->loader->add_action( 'wp_ajax_get_sln_payment_methods', $plugin_admin, 'get_sln_payment_methods' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_sln_payment_methods', $plugin_admin, 'get_sln_payment_methods' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'sln_gcash_saving_data' );
		$this->loader->add_filter( 'manage_edit-sln_booking_columns', $plugin_admin, 'sln_gcash_reciept_add_column' );
		$this->loader->add_action( 'manage_sln_booking_posts_custom_column', $plugin_admin, 'sln_gcash_reciept_add_column_data',);
		$this->loader->add_action( 'wp_ajax_sln_get_gcash_reciept_by_id', $plugin_admin, 'sln_get_gcash_reciept_by_id',);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Salon_Booking_Add_On_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'sln_before_payment_buttons', $plugin_public, 'add_btn_pay_with_gcash' );
		$this->loader->add_action( 'wp_ajax_set_sln_service_id', $plugin_public, 'set_sln_service_id',);
		$this->loader->add_action( 'wp_ajax_nopriv_set_sln_service_id', $plugin_public, 'set_sln_service_id',);
		$this->loader->add_action( 'wp_ajax_get_sln_payment_methods', $plugin_public, 'get_sln_payment_methods' );
		$this->loader->add_action( 'wp_ajax_set_sln_shop_id', $plugin_public, 'set_sln_shop_id',);
		$this->loader->add_action( 'wp_ajax_nopriv_set_sln_shop_id', $plugin_public, 'set_sln_shop_id',);
		$this->loader->add_action( 'wp_ajax_upload_gcash_reciept', $plugin_public, 'upload_gcash_reciept',);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Salon_Booking_Add_On_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}