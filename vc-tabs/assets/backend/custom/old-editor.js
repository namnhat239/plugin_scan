jQuery.noConflict();
(function ($) {
    "use strict";
    var urlParams = new URLSearchParams(window.location.search);
    var styleid = urlParams.get("styleid");
    var childid = "";
    function NEWRegExp(par = '') {
        return new RegExp(par, "g");
    }

    function replaceStr(str, find, replace) {
        for (var i = 0; i < find.length; i++) {
            str = str.replace(new RegExp(find[i], 'gi'), replace[i]);
        }
        return str;
    }

  async function OxiAddonsTemplateSettings(functionname, rawdata, styleid, childid, callback) {
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
    $("#oxi-addons-rearrange-data-modal-open").on("click", function () {
        var functionname = "elements_older_rearrange_modal_data";
        $("#modal-rearrange-store-file").hide();
        $("#oxi-addons-list-rearrange-saving").show();
        $("#oxi-addons-modal-rearrange").hide();
        $("#oxi-addons-list-rearrange-modal").modal("show");
        $("#oxi-addons-modal-rearrange").html('');
        var d = $("#modal-rearrange-store-file").html();
        var rawdata = $("#modal-rearrange-store-file .list-group-item").attr('data-mod');
        $("#oxi-addons-list-rearrange-data").val('');
        OxiAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            console.log(callback);
            var $number = 1;
            $.each($.parseJSON(callback), function (key, value) {
                var data = d.replace(NEWRegExp("{{id}}"), key);
                if (value === '') {
                    value = 'Rearrange Title ' + $number;
                }
                data = data.replace(NEWRegExp('{{TITLE}}'), value);
                $("#oxi-addons-modal-rearrange").append(data);
                $("#oxi-addons-list-rearrange-saving").hide();
                $("#oxi-addons-modal-rearrange").show();
                $number++;
            });
        });
    });

    setTimeout(function () {
        $("#oxi-addons-modal-rearrange").sortable({
            axis: 'y',
            update: function (event, ui) {
                var list_sortable = $(this).sortable('toArray').toString();
                $('#oxi-addons-list-rearrange-data').val(list_sortable);
            }
        });
    }, 500);

    $("#oxi-addons-list-rearrange-submit").on("click", function (e) {
        e.preventDefault();
        $(this).val('Savings..');
        var rawdata = $('#oxi-addons-list-rearrange-data').val();
        if (rawdata === '') {
            alert('Kindly Rearrange, Then  Click to saved');
            return false;
        }
        var functionname = "elements_template_rearrange_save_data";
        OxiAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            console.log(callback);
            if (callback === "success") {
                location.reload();
            }
        });
    });
    $('#oxi-addons-list-data-modal-open').on("click", function (e) {
        e.preventDefault();
        $("#oxilab-add-new-data").modal("show");

    });

    jQuery('.oxilab-vendor-color').each(function () {
        jQuery(this).minicolors({
            control: jQuery(this).attr('data-control') || 'hue',
            defaultValue: jQuery(this).attr('data-defaultValue') || '',
            format: jQuery(this).attr('data-format') || 'hex',
            keywords: jQuery(this).attr('data-keywords') || 'transparent' || 'initial' || 'inherit',
            inline: jQuery(this).attr('data-inline') === 'true',
            letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
            opacity: jQuery(this).attr('data-opacity'),
            position: jQuery(this).attr('data-position') || 'bottom left',
            swatches: jQuery(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function (value, opacity) {
                if (!value)
                    return;
                if (opacity)
                    value += ', ' + opacity;
                if (typeof console === 'object') {
                    // console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    });

    $(".oxi-addons-tabs-ul li:first").addClass("active");
    $(".oxi-addons-tabs-content-tabs:first").addClass("active");
    $(".oxi-addons-tabs-ul li").click(function () {
        if ($(this).hasClass('active')) {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).removeClass("active");
        } else {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            $(this).toggleClass("active");
            $(".oxi-addons-tabs-content-tabs").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).addClass("active");
        }
    });
    $(".oxi-addons-form-heading").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $(".oxi-head").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });

    jQuery("#oxilab-preview-data-background").on('change', function (e) {
        e.preventDefault();
        $('#oxi-addons-preview-data').css("background-color", $(this).val());
    });


})(jQuery);