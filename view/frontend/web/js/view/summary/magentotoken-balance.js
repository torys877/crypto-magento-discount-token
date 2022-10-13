/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals'
], function (Component, totals) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Crypto_MagentoToken/summary/magentotoken-balance',
            storeCreditFormName: 'checkout.steps.billing-step.payment.afterMethods.magentotokenCredit',
            modules: {
                storeCreditForm: '${ $.magentotokenCreditFormName }'
            }
        },
        totals: totals.totals(),

        /**
         * Used balance without any formatting
         *
         * @return {Number}
         */
        getPureValue: function () {
            var price = 0,
                segment;

            if (this.totals) {
                segment = totals.getSegment('magentotoken');

                if (segment) {
                    price = segment.value;
                }
            }

            return price;
        },

        getTokenValue: function () {
            var tokens = 0,
                segment;

            if (this.totals) {
                segment = totals.getSegment('magentotoken');

                if (segment) {
                    tokens = window.checkoutConfig.totalsData.magentotoken.token_value;
                }
            }

            return tokens;
        },

        /**
         * Used balance with currency sign and localization
         *
         * @return {String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        },

        /**
         * Availability status
         *
         * @returns {Boolean}
         */
        isAvailable: function () {
            return this.isFullMode() && this.getPureValue() != 0; //eslint-disable-line eqeqeq
        }
    });
});
