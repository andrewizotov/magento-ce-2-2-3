define(
    [
        'ko',
        'uiComponent',
        'newsletterSubmit'
    ],
    function (ko, Component, Newsletter) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'CoxAndCox_MarketingPreferences/checkout/newsletter-signup'
            },

            newsletterChecked: ko.observable(true),
            thirdParty: ko.observable(false),

            check: function () {
                this.newsletterChecked(true);
                Newsletter.newsletterUpdate(true);
            },

            uncheck: function () {
                this.newsletterChecked(false);
                Newsletter.newsletterUpdate(false);
            },

            thirdPartyUpdate: function () {
                Newsletter.thirdPartyUpdate(this.thirdParty());
                return true;
            }
        });
    }
);
