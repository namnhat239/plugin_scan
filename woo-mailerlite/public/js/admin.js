jQuery(document).ready(function(a) {
    function b() {
        h -= i, k = 100 * (g - h) / g;
        var a = j.find("div");
        a.css("width", k + "%");
    }
    var c = a("#woocommerce_mailerlite_group");
    if (0 !== c.length) {
        var d = c.next(".select2-container");
        a('<span id="woo-ml-refresh-groups" class="woo-ml-icon-refresh" data-woo-ml-refresh-groups="true"></span>').insertAfter(d);
    }
    var e = !1;
    a(document).on("click", "[data-woo-ml-refresh-groups]", function(b) {
        if (b.preventDefault(), !e) {
            var c = a(this);
            c.removeClass("error"), c.addClass("running"), e = !0, jQuery.ajax({
                url: woo_ml_post.ajax_url,
                type: "post",
                data: {
                    action: "post_woo_ml_refresh_groups"
                },
                success: function(a) {
                    a.indexOf("success") >= 0 && c.removeClass("running"), a ? location.reload() : c.addClass("error"), 
                    e = !1;
                }
            });
        }
    });

    a(document).on('click', '[data-woo-ml-reset-orders-sync]', function(event) {

        event.preventDefault();

        jQuery(this).prop('disabled', true);
        jQuery(this).text("Please wait. Do not close this window until the reset finishes.");

        resetOrdersSync();
    });

    var resetOrdersSync = function() {

        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: 'post',
            data: {
                action: 'post_woo_ml_reset_orders_sync'
            },
            async: true,
            success: function (responseStr) {

                var response = JSON.parse(responseStr);

                if (response.allDone) {

                    window.location.reload();
                } else {

                    resetOrdersSync();
                }
            }
        });
    }

    var f = !1, g = 0, h = 0, i = 0, j = a("#woo-ml-sync-untracked-orders-progress-bar"), k = 0;
    a(document).on("click", "[data-woo-ml-sync-untracked-orders]", function(c) {

        c.preventDefault();

        syncOrders(this);
    });

    var syncOrders = function(el) {

        var d = a(el);
        orders_tracked = d.data("woo-ml-untracked-orders-count");
        all_untracked_orders_left = d.data("woo-ml-untracked-orders-left");
        i = d.data("woo-ml-untracked-orders-cycle");
        fail = a('#woo-ml-sync-untracked-orders-fail');
        success = a("#woo-ml-sync-untracked-orders-success");
        r = 0;
        console.log("inside the loop!");
        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: "post",
            beforeSend: function() {
                d.prop("disabled", !0);
                j.show();
                r++;
            },
            data: {
                action: "post_woo_ml_sync_untracked_orders"
            },
            async:1,
            success: function(data) {

                var response = JSON.parse(data);

                if (response.allDone) {

                    console.log("done!");
                    console.log("Response: True");
                    d.hide();
                    j.hide();
                    fail.hide();
                    success.show();
                } else if (response.error) {


                    if (response.message) {
                        fail.html(response.message);
                    }

                    d.prop('disabled', 0);
                    fail.show();
                } else {

                    syncOrders(el);
                }
            }
        });
    };

    a(document).on('click', '[data-woo-ml-reset-products-sync]', function(event) {

        event.preventDefault();

        jQuery(this).prop('disabled', true);
        jQuery(this).text("Please wait. Do not close this window until the reset finishes.");

        resetProductsSync();
    });

    var resetProductsSync = function() {

        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: 'post',
            data: {
                action: 'post_woo_ml_reset_products_sync'
            },
            async: true,
            success: function (responseStr) {

                var response = JSON.parse(responseStr);

                if (response.allDone) {

                    window.location.reload();
                } else {

                    resetProductsSync();
                }
            }
        });
    }

    let p_cycle = 0, p_pbar = a("#woo-ml-sync-untracked-products-progress-bar");
    a(document).on("click", "[data-woo-ml-sync-untracked-products]", function(c) {

        console.log('SYNC PRODUCT !!!');
        c.preventDefault();

        syncProducts(this);
    });

    var syncProducts = function(el) {

        var d = a(el);
        products_tracked = d.data("woo-ml-untracked-products-count");
        all_untracked_products_left = d.data("woo-ml-untracked-products-left");
        p_cycle = d.data("woo-ml-untracked-products-cycle");
        fail = a('#woo-ml-sync-untracked-products-fail');
        success = a("#woo-ml-sync-untracked-products-success");
        r = 0;
        console.log("inside the loop (products)!");
        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: "post",
            beforeSend: function() {
                d.prop("disabled", !0);
                p_pbar.show();
                r++;
            },
            data: {
                action: "post_woo_ml_sync_untracked_products"
            },
            async:1,
            success: function(data) {

                var response = JSON.parse(data);

                if (response.allDone) {

                    console.log("done!");
                    console.log("Response: True");
                    d.hide();
                    p_pbar.hide();
                    fail.hide();
                    success.show();
                } else if (response.error) {

                    if (response.message) {
                        fail.html(response.message);
                    }

                    d.prop('disabled', 0);
                    fail.show();
                } else {

                    syncProducts(el);
                }
            }
        });
    };

    a(document).on('click', '[data-woo-ml-reset-categories-sync]', function(event) {

        event.preventDefault();

        jQuery(this).prop('disabled', true);
        jQuery(this).text("Please wait. Do not close this window until the reset finishes.");

        resetCategoriesSync();
    });

    var resetCategoriesSync = function() {

        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: 'post',
            data: {
                action: 'post_woo_ml_reset_categories_sync'
            },
            async: true,
            success: function (responseStr) {

                var response = JSON.parse(responseStr);

                if (response.allDone) {

                    window.location.reload();
                } else {

                    resetProductsSync();
                }
            }
        });
    }

    let c_cycle = 0, c_pbar = a("#woo-ml-sync-untracked-categories-progress-bar");
    a(document).on("click", "[data-woo-ml-sync-untracked-categories]", function(c) {

        console.log('SYNC Categories !!!');
        c.preventDefault();

        syncCategories(this);
    });

    var syncCategories = function(el) {

        var d = a(el);
        categories_tracked = d.data("woo-ml-untracked-categories-count");
        all_untracked_categories_left = d.data("woo-ml-untracked-categories-left");
        c_cycle = d.data("woo-ml-untracked-categories-cycle");
        fail = a('#woo-ml-sync-untracked-categories-fail');
        success = a("#woo-ml-sync-untracked-categories-success");
        r = 0;
        console.log("inside the loop (categories)!");
        jQuery.ajax({
            url: woo_ml_post.ajax_url,
            type: "post",
            beforeSend: function() {
                d.prop("disabled", !0);
                c_pbar.show();
                r++;
            },
            data: {
                action: "post_woo_ml_sync_untracked_categories"
            },
            async:1,
            success: function(data) {

                var response = JSON.parse(data);

                if (response.allDone) {

                    console.log("done!");
                    console.log("Response: True");
                    d.hide();
                    c_pbar.hide();
                    fail.hide();
                    success.show();
                } else if (response.error) {

                    d.prop('disabled', 0);
                    fail.show();
                } else {

                    syncCategories(el);
                }
            }
        });
    };
    
    var field = a("#woocommerce_mailerlite_api_key");
    a('<button id="woo-ml-validate-key" class="button-primary">Validate Key</button>').insertAfter(field);
    a(document).on("click", "#woo-ml-validate-key", function(b) {
        if (b.preventDefault(), !e) {
            var key = a("#woocommerce_mailerlite_api_key").val();
            jQuery.ajax({
                url: woo_ml_post.ajax_url,
                type:"post",
                data: {
                    action: "post_woo_ml_validate_key",
                    key:key
                },
                async: !1,
                success: function(a) {
                    location.reload()
                }
            })
        }
    });
    if (a('#woocommerce_mailerlite_group').length > 0) {
        a('#woocommerce_mailerlite_group').select2();
    }

    if (a('#woocommerce_mailerlite_ignore_product_list').length > 0) {
        a('#woocommerce_mailerlite_ignore_product_list').select2();
    }
    
    var cs_field = a('#woocommerce_mailerlite_consumer_secret');
    if (0 !== cs_field.length) {
        var field_desc = cs_field.next(".description");
        field_desc.closest('tr').after(
                                    '<h2>Integration Details</h2>\
                                    <p class="section-description">Customize MailerLite integration for WooCommerce</p>');
    }

    var ml_platform = '';

    if (a('#ml_platform').length > 0) {
        ml_platform = a('#ml_platform').val();
    }

    var tracking_field = a('#woocommerce_mailerlite_popups');
    
    tracking_field.closest('tr').before(
                                        '<h2>Popups</h2>\
                                        <p class="section-description">Display pop-up subscribe forms created within MailerLite</p>');

    var button = a('[name="save"]');
    if (field.length !== 0 && (cs_field.length === 0 && ml_platform !== '2')) {
        button.hide();
    } else {
        button.show();
    }
    
    var ignored_p_field = a('#woocommerce_mailerlite_ignore_product_list');
    if (ignored_p_field.length !== 0) {
        ignored_p_field.closest('tr').before(
            '<h2>E-commerce Automations</h2>\
            <p class="section-description">Customize settings for your e-commerce automations created in MailerLite </p>'
        )
    }
});