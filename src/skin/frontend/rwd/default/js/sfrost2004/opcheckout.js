/**
 * Sfrost2004
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact Sfrost2004 for more information.
 *
 * @category    Sfrost2004
 * @package     mdcmk91-skip-checkout-step.local
 * @copyright   Copyright (c) 2016 Sfrost2004
 * @author      Sfrost2004
 *
 */
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
