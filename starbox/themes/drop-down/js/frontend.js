var abh_loadbox_loaded = false;
(function ($) {
    $._getCookie = function (nombre) {
        var dcookie = document.cookie;
        var cname = nombre + "=";
        var longitud = dcookie.length;
        var inicio = 0;
        while (inicio < longitud) {
            var vbegin = inicio + cname.length;
            if (dcookie.substring(inicio, vbegin) === cname) {
                var vend = dcookie.indexOf(";", vbegin);
                if (vend === -1)
                    vend = longitud;
                return unescape(dcookie.substring(vbegin, vend));
            }
            inicio = dcookie.indexOf(" ", inicio) + 1;
            if (inicio === 0)
                break;
        }
        return null;
    };
    $._setCookie = function (name, value) {
        document.cookie = name + "=" + value + "; expires=" + (60 * 24) + "; path=/";
    };
    $.abh_showContent = function (obj) {
        obj.find(".abh_tabs").show();
        obj.find(".abh_job").show();
        obj.find(".abh_allposts").show();
        obj.find(".abh_social").show();
        obj.find(".abh_pwb").show();
        obj.find(".abh_tab_content").css('border-bottom-width', '1px');
        obj.find(".abh_tab_content .abh_name").css('border-bottom-width', '0px');
        obj.find(".abh_description").slideDown('fast');
        obj.find(".abh_arrow").addClass('abh_active');
    };
    $.abh_hideContent = function (obj) {
        obj.find(".abh_description").slideUp('fast', function () {
            obj.find(".abh_tabs").hide();
            obj.find(".abh_job").hide();
            obj.find(".abh_allposts").hide();
            obj.find(".abh_social").hide();
            obj.find(".abh_pwb").hide();
            obj.find(".abh_tab_content").css('border-bottom-width', '0px');
            obj.find(".abh_tab_content .abh_name").css('border-bottom-width', '1px');
            obj.find(".abh_arrow").removeClass('abh_active');
        });
    };
})(jQuery);

function abh_loadbox() {
    abh_loadbox_loaded = true;
    jQuery(".abh_tab_content .abh_about_tab .abh_name").on('click', function (event) {
        event.preventDefault();
        if (jQuery(this).parents('.abh_box').find(".abh_tabs").is(':visible')) {
            jQuery.abh_hideContent(jQuery(this).parents('.abh_box'));
        } else {
            jQuery.abh_showContent(jQuery(this).parents('.abh_box'));
        }
    });
    jQuery(".abh_tab_content .abh_about_tab .abh_image img, .abh_tab_content .abh_posts_tab .abh_image img, .abh_posts_tab .abh_name").on('click', function (event) {
        if (jQuery(this).parents('.abh_box').find(".abh_tabs").is(':visible')) {
            jQuery.abh_hideContent(jQuery(this).parents('.abh_box'));
        } else {
            jQuery.abh_showContent(jQuery(this).parents('.abh_box'));
        }
    });
    jQuery(".abh_tabs li").click(function (event) {
        event.preventDefault();
        jQuery(".abh_tabs li").removeClass('abh_active');
        jQuery(this).addClass("abh_active");
        jQuery(this).parents('.abh_box').find(".abh_tab").hide();
        var selected_tab = jQuery(this).find("a").attr("href");
        jQuery(this).parents('.abh_box').find(selected_tab.replace('#', '.') + '_tab').fadeIn();
        jQuery(this).parents('.abh_box').find(selected_tab.replace('#', '.') + '_tab').parents('.abh_box').find(selected_tab.replace('#', '.')).addClass("abh_active");
        jQuery._setCookie('abh_tab', selected_tab);
        return false;
    });
    jQuery(".abh_tab_content").find(".abh_name").append('<span class="abh_arrow"></span>');
}

jQuery(document).ready(function () {
    if (abh_loadbox_loaded === false)
        abh_loadbox();
});
var abh_timeout_loadbox = setTimeout(function () {
    if (abh_loadbox_loaded === false)
        abh_loadbox(); else
        clearTimeout(abh_timeout_loadbox);
}, 1000);