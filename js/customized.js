(function($) {
    
    wp.customize('ql_title_tagline[title_margin]', function(value) {
        value.bind(function(newval) {
            $('#site-title').css('margin-bottom', newval + 'px');
        });
    });

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
		content = '<p>'+content;
		content = content.replace(/\n/g, '</p><p>');
		content += '</p>';
			
		return content;
	}
		
		// Content
		
			function sliderImage(id, url){
			if(url.length != 0){
				if($('#slider-image-'+id).length > 0){
					$('#slider-image-'+id).attr('src', url);
				}else{
					$('#coin-slider').append('<img id="slider-image-'+id+'" src="'+url+'" />');
				}
			}else{
				$('#slider-image-'+id).remove();
			}
			
			// restart coinslider
			//$('#coin-slider').coinslider({width:468, links:false});
		      $('#coin-slider').flexslider({
		        animation: "slide",
		        animationLoop: false,
		        itemWidth: 210,
		        itemMargin: 5,
		        pausePlay: true,
		        start: function(slider){
		          $('body').removeClass('loading');
		        }
		      });
		}
	
		// Slider
		wp.customize('ql_widgets[slider]', function (value){ value.bind(function(to){ if(to) $('#image-slider').show(); else $('#image-slider').hide(); }); });
		wp.customize('ql_widgets[slider_image_1]', function (value){ value.bind(function(to){ sliderImage(1, to); }); });
		wp.customize('ql_widgets[slider_image_2]', function (value){ value.bind(function(to){ sliderImage(2, to); }); });
		wp.customize('ql_widgets[slider_image_3]', function (value){ value.bind(function(to){ sliderImage(3, to); }); });
		wp.customize('ql_widgets[slider_image_4]', function (value){ value.bind(function(to){ sliderImage(4, to); }); });

    
})(jQuery);
