!function($){function i(i){return jQuery.ajaxSetup({async:!1}),jQuery.post("<?php echo get_template_directory_uri(); ?>","postcontent="+i,function(e){i=e}),jQuery.ajaxSetup({async:!0}),i}function e(i,e){0!=e.length?$("#slider-image-"+i).length>0?$("img[id=slider-image-"+i+"]").attr("src",e):(jQuery('<div id="coin-slider" class="mycoinslider"></div>').insertAfter(".mycoinslider"),jQuery(".mycoinslider:eq(0)").remove(),$("#coin-slider").append(mycoin2),$("#coin-slider").find("ul.slides").append('<li><img id="slider-image-'+i+'" src="'+e+'" style="max-width:460px;height:288px"/></li>')):$("#slider-image-"+i).remove(),jQuery("#coin-slider").flexslider({animation:"slide"})}wp.customize("ql_title_tagline[header_bottom_margin]",function(i){i.bind(function(i){$("header").css("margin-bottom",i+"px")})}),wp.customize("ql_content[line_spacing]",function(i){i.bind(function(i){$("#page-content p").css("line-height",i+"px")})}),wp.customize("ql_content[positiontext]",function(i){i.bind(function(i){$("#page-content").css("text-align",i)})}),wp.customize("ql_content[headerthreecolor]",function(i){i.bind(function(i){$("#page-content h3").css("color",i)})}),wp.customize("ql_content[content]",function(e){e.bind(function(e){$("#page-content").html(i(e))})}),wp.customize("ql_content[headerthreesize]",function(i){i.bind(function(i){$("#page-content h3").css("font-size",i+"px")})}),wp.customize("ql_emailsign[emailtiptext]",function(i){i.bind(function(i){$("input[name=email]").attr("placeholder",i)})}),wp.customize("ql_emailsign[emailtipposition]",function(i){i.bind(function(i){"belowslider"==i?($(".email-top").removeClass("hidden"),$(".email-bottom").addClass("hidden")):"bottom"==i?($(".email-top").addClass("hidden"),$(".email-bottom").removeClass("hidden")):$(".email-top,.email-bottom").removeClass("hidden")})}),wp.customize("ql_widgets[slider]",function(i){i.bind(function(i){i?$("#image-slider").show():$("#image-slider").hide()})}),wp.customize("ql_widgets[slider_image_1]",function(i){i.bind(function(i){e(1,i)})}),wp.customize("ql_widgets[slider_image_2]",function(i){i.bind(function(i){e(2,i)})}),wp.customize("ql_widgets[slider_image_3]",function(i){i.bind(function(i){e(3,i)})}),wp.customize("ql_widgets[slider_image_4]",function(i){i.bind(function(i){e(4,i)})})}(jQuery);