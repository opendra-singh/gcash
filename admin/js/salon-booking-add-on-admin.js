(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	function parseQuery(queryString) {
		var query = {};
		var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
		for (var i = 0; i < pairs.length; i++) {
			var pair = pairs[i].split('=');
			query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
		}
		return query;
	}

	function isJsonString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}

	$(document).ready(function () {

		$(document).on("click", "#sln_service-details_gcash_upload_qr", function (e) {
			e.preventDefault();
			const element = $(this);
			if (element.text() == "Remove") {
				element.siblings("img#sln_service-details_gcash_show_qr").css({ "display": "none" });
				element.siblings("input.sln_service-details_gcash_content-img-url").val('');
				element.text("Upload QR Code");
			} else {
				// Create Custom uplader
				const custom_uploader = wp.media(
					{
						title: "Select an Image",
						button: {
							text: 'Use this Image'
						},
						multiple: false
					}
				);

				if (custom_uploader) {
					custom_uploader.open();
					// Open Custom uplader
					custom_uploader.on(
						"select",
						function () {
							// https://'+window.location.hostname+'/wp-content/plugins/salon-booking-add-on/assets/images/delete.png

							// On select in custom uploader
							const attachment = custom_uploader.state().get("selection").first().toJSON();
							element.siblings("img#sln_service-details_gcash_show_qr").attr("src", attachment.url);
							element.siblings("input[name='"+$(element.siblings()[1]).attr("name")+"']").val(attachment.url);
							element.siblings("img#sln_service-details_gcash_show_qr").css({ "display": "block" });
							element.text("Remove")
						}
					);
				}
			}
		})

		// GCash Reciept Modal View JS in Booking List

		// Get the modal
		const modal = $(document).find(".booking_gcash_reciept_modal");


		// When the user clicks on the button, open the modal
		$(document).find(".booking_gcash_reciept_modal_btn").on("click", function (e) {
			e.preventDefault();
			const element = $(this);
			element.attr("disabled", true);
			element.siblings("img.booking_gcash_reciept_loader").css({ "display": "block" });
			const url = element.parent("td").siblings("td.myauthor").children("a").attr("href");
			$.post(ajax_object.ajax_url, { action: "sln_get_gcash_reciept_by_id", id: parseQuery(url).id }, function (response) {
				if (JSON.parse(response).url) {
					element.siblings("div.booking_gcash_reciept_modal").children("div.booking_gcash_reciept_modal_content").children("img").attr("src", JSON.parse(response).url);
					element.siblings("div.booking_gcash_reciept_modal").css({ "display": "block" });
				} else {
					element.siblings("div.booking_gcash_reciept_modal").children("div.booking_gcash_reciept_modal_content").children("img").css({ "display": "none" });
					element.siblings("div.booking_gcash_reciept_modal").children("div.booking_gcash_reciept_modal_content").children("p.booking_gcash_reciept_modal_error_message").css({ "display": "block" });
					element.siblings("div.booking_gcash_reciept_modal").children("div.booking_gcash_reciept_modal_content").children("p.booking_gcash_reciept_modal_error_message").text("No Receipt Available");
					element.siblings("div.booking_gcash_reciept_modal").css({ "display": "block" });
				}
				element.attr("disabled", false);
				element.siblings("img.booking_gcash_reciept_loader").css({ "display": "none" });
			})
		})

		// When the user clicks on <span> (x), close the modal
		$(document).find(".booking_gcash_reciept_modal_close").on("click", function () {
			$(this).parent("div").parent("div").css({ "display": "none" });
		})

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function (event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}

		// let method = ""; 
		// $.post(ajax_object.ajax_url, { action: "get_sln_payment_methods" }, function (response) {
		// 	method = response;
		// })

		// setTimeout(() => {
		// 	const obj = method.split(",");
		// 	const gcash = obj.includes("gcash") ? "checked='checked'" : "";
		// 	const paypal = obj.includes("paypal") ? "checked='checked'" : "";
		// 	const stripe = obj.includes("stripe") ? "checked='checked'" : "";
		// 	$('#sln-payment_methods').find('.salon_settings_pay_method').parent().prepend('<div class="sln-checkbox" style="width:30%;" ><h6 class="sln-gst-label">GCash</h6><input type="checkbox" name="salon_settings[pay_method]" id="salon_settings_availability_mode--gcash" value="gcash" '+gcash+'><label for="salon_settings_availability_mode--gcash"><span>GCash</span></label></div>');
		// 	$('#sln-payment_methods').find('.salon_settings_pay_method').parent().prepend('<div class="sln-checkbox" style="width:30%;" ><h6 class="sln-gst-label">Paypal</h6><input type="checkbox" name="salon_settings[pay_method]" id="salon_settings_availability_mode--paypal" value="paypal" '+paypal+'><label for="salon_settings_availability_mode--paypal"><span>Paypal</span></label></div>');
		// 	$('#sln-payment_methods').find('.salon_settings_pay_method').parent().prepend('<div class="sln-checkbox" style="width:30%;" ><h6 class="sln-gst-label">Stripe</h6><input type="checkbox" name="salon_settings[pay_method]" id="salon_settings_availability_mode--stripe" value="stripe" '+stripe+'><label for="salon_settings_availability_mode--stripe"><span>Stripe</span></label></div>');
		// 	$('#sln-payment_methods').find('.salon_settings_pay_method').parent().css({"display":"flex"});
		// 	$('#sln-payment_methods').find('.salon_settings_pay_method').remove();
		// }, 3000);

		// $(document).on("change", "input[name='salon_settings[pay_method]']", function() {
		// 	const element = $(this);
		// 	let key = "";
		// 	if (element.is(":checked")) {
		// 		key = "checked";
		// 	}
		// 	$.post(ajax_object.ajax_url, { action: "set_sln_payment_methods", method: element.val(), key : key }, function (response) {
		// 		console.log(response);
		// 	})
		// })

		if (window.location.href.includes("action=edit") || window.location.href.includes("sln_service")) {
			const div = document.getElementsByClassName('sln-box')
			for (let i = 0; i < div.length; i++) {
				const element = div[i];
				const sln_box = element.querySelectorAll(".sln-box--haspanel__header")[0];
				if (sln_box != undefined) {
					$(sln_box.nextElementSibling.querySelectorAll(".sln-service-price-time").item(0)).after(document.getElementById("sln_service-details_gcash_content").cloneNode(true))
					$(sln_box.nextElementSibling.querySelectorAll(".sln-service-price-time").item(0)).after('<div class="sln-separator"></div><h2>GCash</h2>');
				}
				let id = $(element.querySelectorAll(".sln-box--haspanel__header")[0]).siblings();
				id = id.length ? id.attr("id") : "";
				const $this = element.querySelectorAll("div.sln_service-details_gcash_content .sln_service-details_gcash_content-img-url").item(0);
				id = id !== undefined ? id.split('-')[1] : "";
				if ($this != null) {
					$($this).attr("name", "sln_service_details_gcash_show_qr-" + id);
				}

				let meta = $(element.querySelectorAll(".sln_service-details_gcash_content-meta-value").item(0)).val();
				if (meta != "" && typeof meta == "string") {
					meta = JSON.parse(meta);
					if (typeof meta == "object") {
						meta.forEach(element => {
							const url = element.split("|")[1];
							if (url !== "") {
								const current = $(document).find("input[name='sln_service_details_gcash_show_qr-"+element.split("|")[0]+"']");
								current.val(url);
								current.siblings("img").attr("src", url);
								current.siblings("img").css({"display" : "block"});
								current.siblings("button").text("Remove")
							}
						});						
					}
				}

			}
			$('#sln_service-details_gcash').parent('div').remove();
		};
	});
}) (jQuery);
