<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://woodevz.com
 * @since      1.0.0
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/admin
 * @author     Woodevz Technologies <shashwat.srivastava@woodevz.com>
 */
class Salon_Booking_Add_On_Admin {

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

		// if (delete_option("sln_payment_method")) {
		// 	if (update_option("sln_payment_method", wp_json_encode(['paypal']))) {
		// 		$this->debug(json_decode(get_option("sln_payment_method", ""), true));
		// 	}			
		// }

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Salon_Booking_Add_On_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Salon_Booking_Add_On_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/salon-booking-add-on-admin.css', array(), $this->version, 'all' );

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
		 * defined in Salon_Booking_Add_On_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Salon_Booking_Add_On_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/salon-booking-add-on-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'ajax_object',
			array(
			'ajax_url' => admin_url('admin-ajax.php'),
			)
		);

	}

	public function sln_gcash_upload_qr(){
		$id = isset($_GET['post']) ? $_GET['post'] : "";
		$meta = get_post_meta($id, "sln_service-qrcode_image_url", true);
		?>
		<div class="sln-box sln-box--main sln-box--haspanel">
			<h2 class="sln-box-title sln-box__paneltitle" id="sln_service-details_gcash">
				GCASH
			</h2>
			<div id="sln_service-details_gcash_content" class="sln-box__panelcollapse collapse sln_service-details_gcash_content" style="height: 48px;">
				<div class="row sln_service-details_gcash_content_row">
					<div class="col-xs-12 form-group sln-select sln_service-details_gcash_content-col">
						<input type="hidden" name="sln_service-details_gcash_show_qr_meta" class="sln_service-details_gcash_content-meta-value" value='<?= $meta;?>'>
						<input type="hidden" name="sln_service-details_gcash_show_qr" class="sln_service-details_gcash_content-img-url" value="">
						<img height="100" id="sln_service-details_gcash_show_qr" src=""><br>
						<button id="sln_service-details_gcash_upload_qr">Upload QR Code</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	function debug($data = []) {
		echo "<pre>";
		print_r($data);
		die;
	}

	// public function get_sln_payment_methods() {
	// 	$methods = json_decode(get_option("sln_payment_method", ""), true);
	// 	echo implode(",", $methods);
	// 	wp_die();
	// }

	// public function set_sln_payment_methods() {
	// 	$methods = json_decode(get_option("sln_payment_method", ""), true);
	// 	if (isset($_POST['key']) && $_POST['key'] == "checked") {
	// 		if (is_array($methods) && !empty($methods)) {
	// 			array_push($methods, $_POST['method']);
	// 		}else{
	// 			$methods = [$_POST['method']];
	// 		}
	// 	}else{
	// 		if (is_array($methods)) {
	// 			if (($key = array_search(trim($_POST['method']), $methods)) !== false) {
	// 				unset($methods[$key]);
	// 			}
	// 		}
	// 	}
	// 	$methods = array_unique($methods);
	// 	$methods = array_filter($methods);
	// 	if (update_option("sln_payment_method", wp_json_encode($methods))) {
	// 		echo "success";
	// 	}else{
	// 		echo "fail";
	// 	}
	// 	wp_die();
	// }

	public function sln_gcash_saving_data($post_id){
		$data = [];
		if (isset($_POST) && is_array($_POST)) {
			foreach($_POST as $key => $post){
				if(str_contains($key, 'sln_service_details_gcash_show_qr')){
					$id = explode('-', $key)[1];
					$data[] = $id . '|' .$post;
				}
			}
		}
		update_post_meta($post_id, "sln_service-qrcode_image_url", wp_json_encode($data));
	}

	public function sln_gcash_reciept_add_column($column){
		$column['booking_gcash_reciept'] = 'GCash Receipt';
		return $column;
	}

	public function sln_gcash_reciept_add_column_data($output){
		if ('booking_gcash_reciept' == $output) {
			echo __('<img width="30" src="'.plugin_dir_url(__DIR__).'assets/images/loader.gif" alt="loader" class="booking_gcash_reciept_loader d-none"><button class="booking_gcash_reciept_modal_btn"><span class="dashicons dashicons-media-document"></span></button><div class="booking_gcash_reciept_modal"><div class="booking_gcash_reciept_modal_content"><span class="booking_gcash_reciept_modal_close">&times;</span><img width="200" src="" alt="GCash Receipt"><p class="d-none booking_gcash_reciept_modal_error_message"></p></div></div>');
		}
		?>
		<?php
		return $output;
	}

	public function sln_get_gcash_reciept_by_id(){
		if (isset($_POST['id']) && !empty($_POST['id'])) {
			echo wp_json_encode([
				'url' => wp_get_attachment_image_url(get_user_meta($_POST['id'], "sln_gcash_payment_reciept", true))
			]);
			exit;
		}
	}

}
