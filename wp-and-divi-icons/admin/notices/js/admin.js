// Admin Notice
jQuery(document).ready(function ($) {
    $('#wp-and-divi-icons-pro-notice'
    ).on('click', '.notice-dismiss', function () {
        jQuery.post(ajaxurl, {action: 'ds-icon-expansion_notice_hide'})
    });
});