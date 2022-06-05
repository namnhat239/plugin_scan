
jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-tabs-support-reviews", function (e) {
        e.preventDefault();
        $.ajax({
            url: oxilab_tabs_admin_notice.ajaxurl,
            type: 'post',
            data: {
                action: 'oxilab_tabs_notice_dissmiss',
                _wpnonce: oxilab_tabs_admin_notice.nonce,
                notice: $(this).attr('sup-data'),
            },
            success: function (response) {
                console.log(response);
                $('.shortcode-addons-review-notice').hide();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
    });
})(jQuery);
