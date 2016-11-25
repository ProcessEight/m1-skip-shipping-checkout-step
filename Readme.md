How the progress block works in Magento Onepage Checkout
===

Flow
----

- When a checkout step is loaded, the relevant JavaScript object is loaded for that step (`Billing`, `Shipping`, `ShippingMethod`, `Payment` or `Review`). This object binds an event listener to the `onSave` callback of the save Ajax request.
```javascript
skin/frontend/base/default/js/opcheckout.js:289

// billing
var Billing = Class.create();
Billing.prototype = {
    initialize: function(form, addressUrl, saveUrl){
        this.form = form;
        if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }
        this.addressUrl = addressUrl;
        this.saveUrl = saveUrl;
        this.onAddressLoad = this.fillForm.bindAsEventListener(this);
        this.onSave = this.nextStep.bindAsEventListener(this); // Bind nextStep() to onSave() event
        this.onComplete = this.resetLoadWaiting.bindAsEventListener(this);
    },
```
- When the customer clicks the Continue button, the `save()` method of the object is called.
``` html
app/design/frontend/base/default/template/checkout/onepage/billing.phtml:197

    <div class="buttons-set" id="billing-buttons-container">
        <!-- Note billing.save() in onclick attribute -->
        <button type="button" title="<?php echo Mage::helper('core')->quoteEscape($this->__('Continue')) ?>" class="button" onclick="billing.save()"><span><span><?php echo $this->__('Continue') ?></span></span></button>
        ...
    </div>
```
- Once this method completes, the event listener calls `nextStep()`. This method receives the Ajax response and passes it to `checkout.setStepResponse()`.
```javascript
skin/frontend/base/default/js/opcheckout.js:388

// billing
var Billing = Class.create();
Billing.prototype = {
    
    // Other methods omitted for brevity
    
    /**
     This method recieves the AJAX response on success.
     There are 3 options: error, redirect or html with shipping options.
     */
    nextStep: function(transport){
        var response = transport.responseJSON || transport.responseText.evalJSON(true) || {};
        ...
        checkout.setStepResponse(response);
        ...
    }
};
```
- `setStepResponse()` handles the JSON response from Mage_Checkout_OnepageController. If the JSON response contains a `goto_section` index, then `checkout.gotoSection()` is called with the section name and the flag true.
```javascript
skin/frontend/base/default/js/opcheckout.js:261

var Checkout = Class.create();
Checkout.prototype = {

    // Other methods omitted for brevity
    
    setStepResponse: function(response){
        ...
        if (response.goto_section) {
            this.gotoSection(response.goto_section, true);
            return true;
        }
        ...
    }
};
```
- In `gotoSection()`, we finally call `reloadProgressBlock()` if the flag from gotoSection was true. Then the relevant section is opened.
```javascript
skin/frontend/base/default/js/opcheckout.js:130

var Checkout = Class.create();
Checkout.prototype = {

    // Other methods omitted for brevity
    
    gotoSection: function (section, reloadProgressBlock) {

        if (reloadProgressBlock) {
            this.reloadProgressBlock(this.currentStep);
        }
        this.currentStep = section;
        var sectionElement = $('opc-' + section);
        sectionElement.addClassName('allow');
        this.accordion.openSection('opc-' + section);
        if(!reloadProgressBlock) {
            this.resetPreviousSteps();
        }
    },
};
```
- The `reloadProgressBlock()` method has two functions: The first is to reload the `toStep` progress block by passing it onto `reloadStep()`. The second is to check if the customer chose the 'Use same shipping as billing address' option, in which case the shipping information step is skipped. The `syncBillingShipping` flag ensures that the shipping progress step is reloaded as well as the billing step. 

```javascript
    /**
     * Reload relevant checkout progress step
     * 
     * @param toStep string One of billing, shipping, shipping_method, payment
     */
    reloadProgressBlock: function(toStep) {
        this.reloadStep(toStep);
        if (this.syncBillingShipping) {
            this.syncBillingShipping = false;
            this.reloadStep('shipping');
        }
    }
```
