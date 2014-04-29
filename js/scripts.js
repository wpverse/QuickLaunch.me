(function($) {

	function is_email(email) {
		
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
		
	}

	$(document).ready(function() {

		ajaxalert=function(a){
			jQuery(".email_status_text").removeClass("hideemail");
			jQuery(".email_status_text").html(a);
		};
		
		$('.newsletter-form').submit(function(evt) {
			
			evt.preventDefault();
			
			var form = $(this);
			var email = $('.email', form).eq(0);

			class_parent=jQuery(this).closest("div");

			if(class_parent.find(".email_status_text").length==0){
				jQuery('<p align="center" class="email_status_text hideemail"></p>').insertBefore(class_parent.find(".email"));
			}
			
			if ( ! is_email(email.val()) ) {
				ajaxalert('Invalid email address.');
				email.focus();
				return;
			}
			
			$('input', form).prop('disabled', true);
			var data = {
				action: 'ql_register_email',
				email: email.val(),
				_wpnonce: QL.reg_email_nonce
			};
			$.post(QL.ajaxurl, data, function(r) {
				if (r.status == 'ok') {
					ajaxalert('Thanks for registering!');
					email.val('');
				}
				else {
					ajaxalert(r.msg);
				}
				$('input', form).prop('disabled', false);
			}, 'json');
			
		});
		
	});

})(jQuery);
