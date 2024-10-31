/* global payflexi_flexible_checkout_frontend, paypal*/
(function ($) {

    'use strict';

    jQuery('#payflexi_popup_info_link').on('click', function() {
        try {
            let payflexi = PayFlexi.info();
            payflexi.show();
            } catch (error) {
            console.log(error.message);
        }
    });

    var payflexi_checkout_payment_submit = false;

    jQuery('#payflexi-checkout-payment-button' ).on('click', function() {
        return PayflexiCheckoutFormHandler();
    });

    jQuery('#payflexi_checkout_payment_form form#order_review' ).submit( function() {
        return PayflexiCheckoutFormHandler();
    });

    function PayflexiCheckoutCustomFields() {
        var meta = {
            title: payflexi_checkout_params.product_names
        };
        if (payflexi_checkout_params.meta_order_id){
            meta['order_id'] = payflexi_checkout_params.meta_order_id;
        }
        if(payflexi_checkout_params.product_descriptions){
          meta['description'] = payflexi_checkout_params.product_descriptions;
        }
        if(payflexi_checkout_params.product_urls){
            meta['product_urls'] = payflexi_checkout_params.product_urls;
        }
        if(payflexi_checkout_params.product_image){
            meta['product_image'] = payflexi_checkout_params.product_image;
        }
        if(payflexi_checkout_params.product_images){
            meta['product_images'] = payflexi_checkout_params.product_images;
        }
        if(payflexi_checkout_params.meta_billing_address){
           meta['billing_address'] = payflexi_checkout_params.meta_billing_address;
        }
        if(payflexi_checkout_params.meta_shipping_address){
            meta['shipping_address'] = payflexi_checkout_params.meta_shipping_address;
        }
        return meta;
    }

    function PayflexiCheckoutFormHandler() {

        if ( payflexi_checkout_payment_submit ) {
            payflexi_checkout_payment_submit = false;
            return true;
        }

        var $form = $('form#payment-form, form#order_review' ),
        payflexi_txnref = $form.find('input.payflexi_txnref' );

        payflexi_txnref.val( '' );

        var amount = Number( payflexi_checkout_params.amount );

        var payflexi_callback = function( response ) {
            $form.append( '<input type="hidden" class="payflexi_txnref" name="payflexi_txnref" value="' + response.reference + '"/>' );
            payflexi_checkout_payment_submit = true;
            $form.submit();

            $( 'body' ).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                },
                css: {
                    cursor: "wait"
                }
            });
        };

        var handler = PayFlexiBnpl.initiate({
            key: payflexi_checkout_params.key,
            amount:amount,
            gateway: 'payflexi',
            title: payflexi_checkout_params.product_names,
            item_url: payflexi_checkout_params.product_url,
            currency: payflexi_checkout_params.currency,
            reference: payflexi_checkout_params.txnref,
            meta: PayflexiCheckoutCustomFields(),
            onSuccess: payflexi_callback,
            onPaymentSuccess: payflexi_callback,
            onRequestDecline: function() {
                window.location.reload();
            },
            onExit: function () {
                console.log('exited');
                window.location.reload();
            },
            onDecline: function (response) {
                console.log(response);
                window.location.reload();
            }
        });

        handler.renderCheckout();

        return false;
    }
    

}(jQuery));


