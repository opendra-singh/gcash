<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://woodevz.com
 * @since      1.0.0
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Salon_Booking_Add_On
 * @subpackage Salon_Booking_Add_On/public
 * @author     Woodevz Technologies <shashwat.srivastava@woodevz.com>
 */
class Salon_Booking_Add_On_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/salon-booking-add-on-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/salon-booking-add-on-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'ajax_object',
			array(
			'ajax_url' => admin_url('admin-ajax.php'),
			)
		);

	}

	// public function get_sln_payment_methods() {
	// 	$methods = json_decode(get_option("sln_payment_method", ""), true);
	// 	echo implode(",", $methods);
	// 	wp_die();
	// }
	
	public function add_btn_pay_with_gcash($paymentMethod){
		$id = (int)trim($_SESSION['sln_service_id']);
		$meta = json_decode(get_post_meta($id, "sln_service-qrcode_image_url", true), true);
		$url = "";
		if (is_array($meta)) {
			foreach ($meta as $single) {
				if ($_SESSION['sln_shop_id'] == explode("|", $single)[0]) {
					$url = explode("|", $single)[1];
				}
			}
		}
		$methods = json_decode(get_option("sln_payment_method", ""), true);
		$display = "none";
		if (in_array("gcash", $methods)) {
			$display = "block";
		}
		?>
		<div id="sln_service_gcash_btn_div" style="display:<?= $display ?>;" class="col-xs-12 col-sm-6 sln-input--action sln-form-actions"><a href="#" id="sln_service_gcash_btn" class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth">Pay with GCash</a></div>

		<div style="display: none;" class="qr-code" id="sln_service_qrcode_div">
			<h3>Scan or Download the QR Code image to pay through the GCash app. Download the receipt. Go back here to upload it and finish your booking.</h3>
				<br>
				<br>
			<img style="height: 200px;width: 200px;border: 2px solid #9a9a9a;" src="<?= $url ;?>">
			<br>
			<br>
			<a href="<?= $url ;?>" class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth" id="sln_service_download_btn" download="<?= basename($url);?>" target="_blank">Download</a>
			<br>
			<br>
		</div>

		<div class="screenshot" style="display: none;" id="sln_service_screenshot_div">
			<form action="<?= $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
				<h3>Scan or Download the QR Code image to pay through the GCash app. Download the receipt. Go back here to upload it and finish your booking.</h3>
				<br>
				<br>
				<label for="sln_service_screenshot_file">Upload/Attach GCash Receipt</label>
				<input type="file" name="sln_service_screenshot_file" id="sln_service_screenshot_file">
				<br>
				<br>
				<button class="sln-btn sln-btn--emphasis sln-btn--noheight sln-btn--fullwidth" id="sln_service_save_screenshot">Save</button>
				<br>
				<br>
			</form>
		</div>

		<script>
			const $ = jQuery;

			$(document).find("#sln_service_gcash_btn").on("click", function(e){
				e.preventDefault();
				$(document).find("#sln_service_qrcode_div").css({"display":"block"});
				$(document).find(".sln-payment-actions").remove();
			});
			$(document).find("#sln_service_download_btn").on("click", function(){
				$(document).find("#sln_service_screenshot_div").css({"display":"block"});
				$(document).find("#sln_service_qrcode_div").css({"display":"none"});
			});

			$(document).find("#sln_service_save_screenshot").on("click", function(e){
				e.preventDefault();
				const element = $(this);
				const file_input = $(document).find("#sln_service_screenshot_file")[0].files[0];
				if (file_input !== undefined) {
					element.attr("disabled", true);
					element.text("Saving...");
					const fd = new FormData;
					fd.append("file", file_input);
					fd.append("action", "upload_gcash_reciept");
					$.ajax({
						url : ajax_object.ajax_url,
						type: 'POST',
						data : fd,
						cache : false,
						processData: false,
						contentType: false,
						success : function(response){
							element.attr("disabled", false);
							element.text("Save");
							$(document).find("#sln_service_screenshot_div").css({"display":"none"});
							$(document).find("#sln_service_gcash_btn_div").css({"display":"none"});
							alert(response);
							window.location.href = window.location.origin + '/thank-you-for-booking'
						},
						error : function(error){
							element.attr("disabled", false);
							element.text("Save");
							$(document).find("#sln_service_screenshot_div").css({"display":"none"});
							$(document).find("#sln_service_gcash_btn_div").css({"display":"none"});
							alert(error);
						}
					});
				}else{
					alert("Please upload reciept first!");
				}
			})
			
		</script>
		<?php
	}

	public function set_sln_service_id(){
		if (isset($_POST['id']) && !empty($_POST['id'])) {
			$_SESSION['sln_service_id'] = $_POST['id'];
			echo $_SESSION['sln_service_id'];
			echo "set_sln_service_id";
		}else{
			echo "id not found";
		}
		exit;
	}

	public function set_sln_shop_id(){
		if (isset($_POST['id']) && !empty($_POST['id'])) {
			$_SESSION['sln_shop_id'] = $_POST['id'];
			echo $_SESSION['sln_shop_id'];
			echo "set_sln_shop_id";
		}else{
			echo "id not found";
		}
		exit;
	}

	public function upload_gcash_reciept(){
		if (isset($_FILES['file']) && !empty($_FILES['file'])) {
			if (is_user_logged_in()) {
				// Include the necessary WordPress files
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/media.php');

				// Prepare the file information
				$file_info = wp_handle_upload($_FILES['file'], array('test_form' => FALSE));

				if (!isset($file_info['error'])) {
					// Create the attachment post
					$attachment = array(
						'post_mime_type' => $file_info['type'],
						'post_title' => sanitize_file_name($file_info['file']),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					// Insert the attachment post
					$attachment_id = wp_insert_attachment($attachment, $file_info['file']);

					// Generate metadata for the attachment
					$attachment_data = wp_generate_attachment_metadata($attachment_id, $file_info['file']);

					// Update the metadata for the attachment
					wp_update_attachment_metadata($attachment_id, $attachment_data);

					// Set the attachment as the user's profile picture
					update_user_meta(get_current_user_id(), 'sln_gcash_payment_reciept', $attachment_id);

					echo wp_json_encode("Receipt Saved Successfully!");

				}else{
					echo wp_json_encode($file_info['error']);
				}

			}else{
				echo wp_json_encode("Please Login first!");
			}
		}else{
			echo wp_json_encode("Please Upload Reciept first!");
		}
		exit();
	}

}
