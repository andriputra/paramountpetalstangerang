(function($) {
    $(window).on('load', function() {
        wp.codeEditor.initialize($('[id="wpheaderandfooter_basics[wp_header_textarea]"]'));
        wp.codeEditor.initialize($('[id="wpheaderandfooter_basics[wp_body_textarea]"]'));
        wp.codeEditor.initialize($('[id="wpheaderandfooter_basics[wp_footer_textarea]"]'));
    });
})(jQuery);