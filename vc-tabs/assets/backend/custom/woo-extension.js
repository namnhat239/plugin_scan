jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';


    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }
    async function Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
                url: oxilabtabsultimate.root + 'oxilabtabsultimate/v1/' + functionname,
                method: 'POST',

                data: {
                    _wpnonce: oxilabtabsultimate.nonce,
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                }
            });
            console.log(result);
            return callback(result);
        } catch (error) {
            console.error(error);
        }
    }

    $("input[name=oxilab_tabs_woocommerce] ").on("change", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = '', $link = $(this).parents('.oxi-sa-cards').children('.responsive_tabs_with_accordions_license_massage');
        if ($(this).is(":checked")) {
            var $value = 'yes';
        }
        var rawdata = JSON.stringify({value: $value});
        var functionname = "oxilab_tabs_woocommerce";
        $link.html('<span class="spinner sa-spinner-open"></span>');
        if ($value === 'yes') {
            $('.oxilab_tabs_woocommerce_active').slideDown();
        } else {
            $('.oxilab_tabs_woocommerce_active').slideUp();
        }
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $link.html(callback);
            setTimeout(function () {
                $link.html('');
            }, 8000);
        });
    });
    $("input[name=oxi_tabs_use_the_content] ").on("change", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = '', $link = $(this).parents('.oxi-sa-cards').children('.responsive_tabs_with_accordions_license_massage');
        if ($(this).is(":checked")) {
            var $value = 'yes';
        }
        var rawdata = JSON.stringify({value: $value});
        var functionname = "oxi_tabs_use_the_content";
        $link.html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $link.html(callback);
            setTimeout(function () {
                $link.html('');
            }, 8000);
        });
    });
    $("#oxilab_tabs_woocommerce_default").on("change", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val(), $link = $(this).parents('.oxi-sa-cards').children('.responsive_tabs_with_accordions_license_massage');
        var rawdata = JSON.stringify({value: $value});
        var functionname = "oxilab_tabs_woocommerce_default";
        $link.html('<span class="spinner sa-spinner-open"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $link.html(callback);
            setTimeout(function () {
                $link.html('');
            }, 8000);
        });
    });


















    $(document.body).on("click", ".oxi-woo-header", function (e) {
        e.preventDefault();
        $(this).parent().toggleClass("oxi-hidden");
    });

    $(document.body).on("keyup", ".oxilab_tabs_woo_layouts_title_field", function (e) {
        $input = $(this);
        $input.parents('.woo-oxi-content').siblings(".oxi-woo-header").children('.oxi-woo-header-text').html($input.val());
    });
    $(".woo-oxilab-tabs-admin-body").sortable({
        axis: 'y',
        handle: ".oxi-woo-header-text"
    });

    $('.oxilab_tabs_woo_layouts_icon_field').iconpicker();

    $("#oxi-addons-customize_default_tabs_form").submit(function (e) {
        e.preventDefault();
        $This = $(this);
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "customize_default_tabs";
        $This.find('.oxi-woo-tabs-add-rows-button').val('Savings');
        $This.find('.oxi-woo-tabs-add-rows').prepend('<span class="spinner sa-spinner-open-left"></span>');
        Oxi_Tabs_Admin(functionname, rawdata, styleid, childid, function (callback) {
            $This.find('.oxi-woo-tabs-add-rows-button').val('Saved');
            setTimeout(function () {
                $This.find('.oxi-woo-tabs-add-rows-button').val('Save Data');
                $This.find('.sa-spinner-open-left').remove();
            }, 3000);
        });
    });


}
)(jQuery)