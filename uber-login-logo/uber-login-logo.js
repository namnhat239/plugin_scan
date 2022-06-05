/**
 * Uber Login Logo
 * Version: 1.5.1
 * Author: Alex Rogers
 * Copyright (c) 2013 - Alex Rogers.  All rights reserved.
 * http://www.uberweb.com.au/uber-login-logo-wordpress-plugin/
 */

'use strict';

var Uber = Uber || {};

(function ($, window, document, undefined) {
    Uber.LoginLogo = {

        config: {
            version: '1.5.1',
            nonce: null,
            editor: null,
            selectedId: 0
        },

        elems: {
            $container: '.uber-login-logo',
            $uploadTrigger: '.upload-image',
            $uploadInput: '#upload-input',
            $nonceInput: '#uber_login_logo_nonce',
            $updateStatus: '.update-status',
            $imgHolder: '.img-holder',
            $imgPreview: '.img-preview'
        },

        init: function() {
            Uber.Tools.setElems(this.elems, this);
            if (!Uber.Tools.doesElemExist(this.elems.$container)) return;

            _.bindAll(this, 'setEditor', 'setNonce', 'getOptions', 'updateOptions', 'showImagePreview', 'preSelectImage', 'catchInsert');

            this.setNonce();
            this.setEditor();
            this.getOptions();
            this.catchInsert();

            // bind events
            var self = this;
            this.elems.$uploadTrigger.on('click', function() {
                self.config.editor.open();

                return false;
            });

            this.config.editor.on('open', this.preSelectImage);
        },

        setEditor: function() {
            this.config.editor = wp.media.editor.add('content');
        },

        setNonce: function() {
            this.config.nonce = this.elems.$nonceInput.val();
        },

        getOptions: function() {
            var self = this;

            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'json',
                data:
                {
                    'action': 'displayPreviewImg',
                    'uber_login_logo_nonce': this.config.nonce
                }
            }).done(function(response) {
                self.showImagePreview(response);
            });
        },

        updateOptions: function(id, size) {
            var self = this;

            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'json',
                data:
                {
                    'action': 'getImageData',
                    'id': id,
                    'size': size,
                    'uber_login_logo_nonce': this.config.nonce
                }
            }).done(function(response) {
                self.showImagePreview(response);
                self.elems.$updateStatus.show();
            });
        },

        showImagePreview: function(response) {
            this.elems.$uploadInput.val(response.src);
            this.elems.$imgPreview.html('<img src="' + response.src + '" />');
            this.elems.$imgHolder.show();
            this.config.selectedId = response.id;
        },

        preSelectImage: function() {
            var selection = this.config.editor.state().get('selection');
            var attachment = wp.media.attachment(this.config.selectedId);
            attachment.fetch();
            selection.add( attachment ? [ attachment ] : [] );
        },

        catchInsert: function() {
            var self = this;
            wp.media.editor.send.attachment = function(props, attachment){
                self.updateOptions(attachment.id, props.size);
            }
        }

    };

    Uber.Tools = {
        setElems: function (selectors, context, $context) {
            context.elems = context.elems || {};
            for (var key in selectors)
                context.elems[key] = $context ? $context.find(selectors[key]) : $(selectors[key]);
        },

        doesElemExist: function(elem) {
            return (typeof elem !== 'undefined' && elem.length);
        }
    };

    //init
    $(function () {
        Uber.LoginLogo.init();
    });

})(jQuery, window, document);