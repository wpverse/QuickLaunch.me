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
    
})(jQuery);