How the progress block works in Magento Onepage Checkout
===
- Each section of the progress block is reloaded by triggering the `reloadProgressBlock()` method of the `Checkout` class in `skin/frontend/base/default/js/opcheckout.js`:

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
This method has two functions: The first is to reload the `toStep` progress block by passing it onto `reloadStep()`.
The second is to check if the customer chose the 'Use same shipping as billing address' option, in which case the shipping information step is skipped. The `syncBillingShipping` flag ensures that the shipping progress step is reloaded as well as the billing step.

Flow
----

- When a checkout step is loaded, an JavaScript object is loaded for that step. This object binds an event listener to the `onSave` callback of the save Ajax request.
- When the customer clicks the Continue button, the `save()` method of the object is called.
- Once this method completes, the event listener calls `nextStep()`. This method receives the Ajax response and passes it to `checkout.setStepResponse()`.
- Inside 