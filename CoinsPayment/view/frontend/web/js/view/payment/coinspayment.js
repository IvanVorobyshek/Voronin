define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'coinspayment',
            component: 'Voronin_CoinsPayment/js/view/payment/method-renderer/coinspayment-method'
        }
    );

    /** Add view logic here if needed */
    return Component.extend({});
});
