jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';

    async function Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, callback) {
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
    jQuery(".btn.btn-danger").on("click", function (e) {
        e.preventDefault();
        $('#oxi-addons-import-data-form').trigger("reset");
        return false;
    });
    //  
    jQuery("#oxi-addons-import-data-form").submit(function (e) {
        e.preventDefault();
        if ($("#oxistyledata").val() === "") {
            alert('Empty Data, Kindly add Export Code');
            return false;
        }
        if ($(".oxi-addons-updated").length === 1) {
            alert('Sorry,  Only Premium Version will works for import Design');
            return false;
        }
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "create_new";
        $('.oxi-buttom').prepend('<span class="spinner sa-spinner-open-left"></span>');
        Oxi_Tabs_Admin_Create(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                document.location.href = callback;
            }, 1000);
        });
    });
})(jQuery);