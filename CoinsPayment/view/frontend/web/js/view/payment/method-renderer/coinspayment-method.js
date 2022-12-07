define([
    'Magento_Checkout/js/view/payment/default',
    'Magento_Checkout/js/model/quote'
], function (Component, $quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Voronin_CoinsPayment/payment/coinspayment'
        },
        cusName: $quote.billingAddress()['firstname'],

        isActive: function () {
            console.log($quote);
            let coins = parseFloat(checkoutConfig.payment.instructions.coins);
            let grandTotal = parseFloat($quote.totals()['grand_total']);
            if (coins >= grandTotal) {
                return true;
            } else {
                return false;
            }
        },

        getInformation () {
            let coins = parseFloat(checkoutConfig.payment.instructions.coins);
            let grandTotal = parseFloat($quote.totals()['grand_total']);
            if (coins >= grandTotal) {
                return '';
            } else {
                return ', you do not have enough coins to pay! You have only: ' + coins + ' coins.';
            }
        },
        funcData: function () {
            return  checkoutConfig.quoteData.base_grand_total
                + ' ' + checkoutConfig.payment.instructions.coins
                + ' ' + checkoutConfig.payment.instructions.coinspayment;
        },


        /**
         * Returns payment method instructions.
         *
         * @return {*}
         */
        getInstructions: function () {
            return window.checkoutConfig.payment.instructions[this.item.method];
        }
    });
});
