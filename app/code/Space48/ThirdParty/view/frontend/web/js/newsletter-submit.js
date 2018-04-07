define([
    'jquery'
], function ($) {
    'use strict'
    return {
        subscribed: 1,

        thirdPartyOk: 19,

        newsletterSubmit: function (config, node) {
            this.url = config.url;

            $(document).ready($.proxy(this.submit, this));
        },

        newsletterUpdate: function (subscribed) {
            this.subscribed = subscribed ? 1 : 0;
            this.submit();
        },

        thirdPartyUpdate: function (thirdPartyOk) {
            this.thirdPartyOk = thirdPartyOk ?  18 : 19;
            this.submit();
        },

        submit: function () {
            var self = this;
            $.ajax({
                url: this.url,
                data: {
                    optIn: self.thirdPartyOk,
                    newsletter: self.subscribed
                },
                type: 'POST'
            });
        }
    }
});
