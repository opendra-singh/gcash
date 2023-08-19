(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	
	$(document).ready(function(){
		// $.ajax({
		// 	url : ajax_object.ajax_url,
		// 	type: 'POST',
		// 	data : {
		// 		action : "get_sln_payment_methods",
		// 	},
		// 	success : function(response){
		// 		localStorage.setItem("sln_payment_methods", response);
		// 	},
		// 	error : function(error){
		// 		console.log(error);
		// 	}
		// });
		
		$(document).ajaxComplete(function(event, xhr) {
			if (xhr.status === 200) {
			  // The request was successful, and the response is available in xhr.responseText
			  console.log(xhr.responseText);
			  // You can process the response here or do anything you want with it

			  if (xhr.responseText.includes("sln-payment-actions")) {
				  $('.sln-payment-actions').css({"display":"none"});
// 				  $('.sln-payment-actions').css({"display" : "none"});
// 				  const obj = localStorage.getItem('sln_payment_methods').split(",");
// 				  console.log(obj);
// 				  const payment_methods = $('.sln-payment-actions');
// 				  if (payment_methods.length) {
// 					  payment_methods.each(function(_index, e) {
// 						  obj.forEach(element => {
// 							  if (element != "") {
// 								  if ($(e).find("a").text().trim().toLowerCase().includes(element)) {
// 									  $(e).css({"display" : "block"});
// 								  }
// 							  }
// 						  });
// 					  });
// 				  }
			  }
			} else {
			  // The request was not successful. Handle errors here.
			  console.error('Request failed with status:', xhr.status);
			}
		});

		if ($(document).find("#sln-step-submit").attr("data-salon-data") == "sln_step_page=shop&submit_shop=next") {
				const id = $(document).find(".sln-select-multishop").val()
			if (id != "") {
				$.post(ajax_object.ajax_url, {action : "set_sln_shop_id", key : "sln_shop_id", id : id}, function(response){
					console.log(response);
				});
			}
		}

		$(document).find(".sln-select-multishop").on("change", function() {
			const id = $(this).val();
			if (id != "") {
				$.post(ajax_object.ajax_url, {action : "set_sln_shop_id", key : "sln_shop_id", id : id}, function(response){
					console.log(response);
				});
			}
		})
		
		$(document).on("change", function(){
			const name = $(document).find(".sln-service-info div.sln-checkbox input[type='checkbox']:checked").attr("name");
			const total_id = name != undefined ? name.split("][")[1] : ""			
			const id = total_id != undefined || total_id != "" ? total_id.split("]")[0] : "";
			if (id.length) {
				$.post(ajax_object.ajax_url, {action : "set_sln_service_id", key : "sln_service_id", id : id}, function(response){
					console.log(response);
				});
			}
		})
	})

})( jQuery );