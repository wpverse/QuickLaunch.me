(function($) {

    function setupSliders() {

        $('.ql-slider').each(function() {

            var el = $(this),
                args = {
                    slide: function(evt, ui) {
                        $('input', this).val(ui.value)
                                        .trigger('change');
                    }
                },
                options = ['min', 'max', 'step'];

            $.each(options, function(i, opt) {
                if (el.data(opt))
                    args[opt] = el.data(opt);
            });

            args['value'] = el.val();

            el.wrap('<div class="ql-slider-container">');

            el.hide()
              .parent().slider(args);

        });

    }

    function setupAligners() {

        $('#customize-control-blogname').append('\
            <ul class="aligner" data-target-controls="_customize-radio-ql_title_tagline[postitle]" data-to-hide="#customize-control-ql_title_tagline-postitle"> \
                <li><a href="#" title="Align Left" data-value="left">&#xf036;</a></li> \
                <li><a href="#" title="Align Center" data-value="center">&#xf037;</a></li> \
                <li><a href="#" title="Align Right" data-value="right">&#xf038;</a></li> \
                <li><a href="#" title="Justify" data-value="justify">&#xf039;</a></li> \
            </ul>');

        $('#customize-control-blogdescription').append('\
            <ul class="aligner" data-target-controls="_customize-radio-ql_title_tagline[postitleinfo]" data-to-hide="#customize-control-ql_title_tagline-postitleinfo"> \
                <li><a href="#" title="Align Left" data-value="left">&#xf036;</a></li> \
                <li><a href="#" title="Align Center" data-value="center">&#xf037;</a></li> \
                <li><a href="#" title="Align Right" data-value="right">&#xf038;</a></li> \
                <li><a href="#" title="Justify" data-value="justify">&#xf039;</a></li> \
            </ul>');

        $('ul.aligner').each(function(i, ul) {
            
            ul = $(this);
            var targetRadios = ul.data('target-controls');

            if (ul.data('to-hide'))
                $(ul.data('to-hide')).hide();

            $('a', ul).each(function() {

                var a = $(this),
                    partnerRadio = $('input[name="'+ targetRadios +'"][value="'+ a.data('value') + '"]');

                a.data('partner', partnerRadio);
                partnerRadio.data('partner', a);

                partnerRadio.on('change', function() {
                    var radio = $(this)
                    radio.data('partner').parent().parent().find('a').removeClass('selected');
                    if (radio.is(':checked'))
                        radio.data('partner').addClass('selected');
                });

                a.on('click', function(evt) {
                    evt.preventDefault();
                    var a = $(this);
                    a.data('partner').prop('checked', true)
                                     .change();
                });

                if (a.data('partner').is(':checked'))
                    a.addClass('selected');

            });

        });


    }

    function setupGradientPickers() {

        gradX('.ql-gradient-picker');

    }

    $(function() {

        setupSliders();
        setupAligners();
        setupGradientPickers();

    });

})(jQuery);