Checkout.prototype.setStepResponse = function(response){
    if (response.update_section) {
        $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
    }
    if (response.allow_sections) {
        response.allow_sections.each(function(e){
            $('opc-'+e).addClassName('allow');
        });
    }

    if(response.duplicateBillingInfo)
    {
        this.syncBillingShipping = true;
        shipping.setSameAsBilling(true);
    }

    if (response.goto_section) {

        /**
         * Reload shipping method section of progress block
         */
        if (response.goto_section == 'payment') {
            this.reloadProgressBlock('shipping_method');
        }

        this.gotoSection(response.goto_section, true);
        return true;
    }
    if (response.redirect) {
        location.href = encodeURI(response.redirect);
        return true;
    }
    return false;
};