/**
 * Reload relevant checkout progress step
 *
 * @param toStep string One of billing, shipping, shipping_method, payment
 */
Checkout.prototype.reloadProgressBlock = function(toStep) {
    this.reloadStep(toStep);
    if (this.syncBillingShipping) {
        this.syncBillingShipping = false;
        this.reloadStep('shipping');
    }
    /**
     * Reload shipping method section of progress block
     */
    if (toStep == 'billing' || toStep == 'shipping') {
        this.reloadStep('shipping_method');
    }
};
