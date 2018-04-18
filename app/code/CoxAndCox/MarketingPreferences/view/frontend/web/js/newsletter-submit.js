define([
    'jquery'
], function ($) {
    'use strict'
    return {
        subscribed: 1,

        thirdPartyOk: 1,

        postalMailingsOk: 1,

        newsletterSubmit: function (config, node) {
            this.url = config.url;

            //$(document).ready($.proxy(this.submit, this));
        },

        newsletterUpdate: function (subscribed) {
            this.subscribed = subscribed ? 1 : 0;
            this.submit();
        },

        thirdPartyUpdate: function (thirdPartyOk) {
            this.thirdPartyOk = thirdPartyOk ?  1 : 0;
            this.submit();
        },

        postalMailingsUpdate: function (postalMailingsOk) {
            this.postalMailingsOk = postalMailingsOk ?  1 : 0;
            this.submit();
        },

        submit: function () {
            var self = this;


            $.ajax({
                url: this.url,
                data: {
                    optIn: self.thirdPartyOk,
                    optInPostalMailings: self.postalMailingsOk,
                    newsletter: self.subscribed
                },
                type: 'POST'
            });
        }
    }
});
