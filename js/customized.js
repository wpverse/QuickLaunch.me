(function($) {

    wp.customize('ql_title_tagline[header_bottom_margin]', function(value) {
        value.bind(function(newval) {
            $('header').css('margin-bottom', newval + 'px');
        });
    });

    wp.customize('ql_content[line_spacing]', function(value) {
        value.bind(function(newval) {
            $('#page-content p').css('line-height', newval + 'px');
        });
    });

    wp.customize('ql_content[positiontext]',function( value ) {
	value.bind(function(to) {
		$('#page-content').css('text-align', to);
    });
    });

    wp.customize('ql_content[headerthreecolor]',function( value ) {
	value.bind(function(to) {
	$('#page-content h3').css('color', to);
    });
    });

    wp.customize('ql_content[content]', function (value){
	value.bind(function(to){
		$('#page-content').html(parseContent(to));
    });
    });
    wp.customize('ql_content[headerthreesize]',function( value ) {
	value.bind(function(to) {
	$('#page-content h3').css('font-size', to+"px");
    });
    });
    wp.customize('ql_emailsign[emailtiptext]', function (value){
	value.bind(function(to){
		$('input[name=email]').attr("placeholder",to);
    });
    });
    wp.customize('ql_emailsign[emailtipposition]', function (value){
	value.bind(function(to){
		if(to=='belowslider'){
			$('.email-top').removeClass("hidden");
			$('.email-bottom').addClass("hidden");
		}else if(to=='bottom'){
			$('.email-top').addClass("hidden");
			$('.email-bottom').removeClass("hidden");
		}else{
			$('.email-top,.email-bottom').removeClass("hidden");
		}
    });
    });
    	/**
	 * Parse plain text to html
	 */
	function parseContent(content){
		// parse links
		//var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
		//content = content.replace(exp,"<a href='$1'>$1</a>");
			
		// add html p tags
		/*content = '<p>'+content;
		content = content.replace(/\n/g, '</p><p>');
		content += '</p>';*/
		jQuery.ajaxSetup({async:false});
		jQuery.post("/wp-content/themes/QuickLaunch.me-master/echowp.php","postcontent="+content,function(data){
			content=data;
		});
		jQuery.ajaxSetup({async:true});
		return content;
			
	}
		
		// Content
		
			function sliderImage(id, url){
			if(url.length != 0){
				if($('#slider-image-'+id).length > 0){
					$('img[id=slider-image-'+id+']').attr("src",url);
				}else{
					jQuery('<div id="coin-slider" class="mycoinslider"></div>').insertAfter(".mycoinslider");
					jQuery(".mycoinslider:eq(0)").remove();
					$('#coin-slider').append(mycoin2);
					$('#coin-slider').find("ul.slides").append('<li><img id="slider-image-'+id+'" src="'+url+'" style="max-width:460px;height:288px"/></li>');
				}
			}else{
				$('#slider-image-'+id).remove();
			}
			
			// restart coinslider
			//$('#coin-slider').coinslider({width:468, links:false});
			jQuery('#coin-slider').flexslider({animation: "slide",});
		}
	
		// Slider
		wp.customize('ql_widgets[slider]', function (value){ value.bind(function(to){ if(to) $('#image-slider').show(); else $('#image-slider').hide(); }); });
		wp.customize('ql_widgets[slider_image_1]', function (value){ value.bind(function(to){ sliderImage(1, to); }); });
		wp.customize('ql_widgets[slider_image_2]', function (value){ value.bind(function(to){ sliderImage(2, to); }); });
		wp.customize('ql_widgets[slider_image_3]', function (value){ value.bind(function(to){ sliderImage(3, to); }); });
		wp.customize('ql_widgets[slider_image_4]', function (value){ value.bind(function(to){ sliderImage(4, to); }); });


})(jQuery);
