Sfrost2004 PaymentSurcharge Extension
=====================
An example module to demonstrate how to add and use total models in Magento

Facts
-----
- version: 1.0.0
- extension key: Sfrost2004_PaymentSurcharge

Detailed Description
-----------
The extension adds a new total to the quote and order; A 'surcharge' based on the payment method chosen.

### Define the totals model in the config XML
Total models should be defined under the `global` node:

```xml
    <global>
        <sales>
            <quote>
                <totals>
                    <!-- Name of your custom total model -->
                    <payment_surcharge>
                        <class>sfrost2004_paymentsurcharge/total_quote_address_payment_surcharge</class>
                        <!-- Total model will be invoked after these totals have been processed -->
                        <after>subtotal,discount</after>
                        <!-- Total model will be invoked before these totals have been processed -->
                        <before>shipping</before>
                    </payment_surcharge>
                </totals>
            </quote>
            <order_invoice>
                <totals>
                    <payment_surcharge>
                        <class>sfrost2004_paymentsurcharge/total_order_invoice_payment_surcharge</class>
                        <after>subtotal,discount</after>
                        <before>shipping</before>
                    </payment_surcharge>
                </totals>
            </order_invoice>
            <order_creditmemo>
                <totals>
                    <payment_surcharge>
                        <class>sfrost2004_paymentsurcharge/total_order_creditmemo_payment_surcharge</class>
                        <after>subtotal,discount</after>
                        <before>shipping</before>
                    </payment_surcharge>
                </totals>
            </order_creditmemo>
        </sales>
        <pdf>
            <totals>
                <payment_surcharge translate="title" module="sfrost2004_paymentsurcharge">
                    <title>Shipping Surcharge</title>
                    <source_field>payment_surcharge_amount</source_field>
                    <font_size>7</font_size>
                    <display_zero>0</display_zero>
                    <sort_order>450</sort_order>
                </payment_surcharge>
            </totals>
        </pdf>
```
Here we define total models for the quote, invoice and credit memo entities and also for the shipping PDF.

### Add the total models
We add three total models for quote, invoice and credit memo entities.

#### Quote total model
```php
class Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
	/**
	 * Calculate surcharge amount
	 *
	 * @param Mage_Sales_Model_Quote_Address $address
	 * @return Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge
	 */
	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);

		$this->_setAmount(0);
		$this->_setBaseAmount(0);
		
		$surchargeRate = 4;

		// You would add a check to see if the surcharge should be applied here
		$store = $address->getQuote()->getStore();
		// This surcharge calculation logic would usually be in a Helper
		$surcharge      = ($address->getSubtotal() / 100) * $surchargeRate;
		$baseSurcharge  = ($address->getBaseSubtotal() / 100) * $surchargeRate;
		if ($surcharge) {
			$this->_setAmount($surcharge);
			$this->_setBaseAmount($baseSurcharge);
		}

		return $this;
	}

	/**
	 * Set values for display on address model
	 *
	 * @param Mage_Sales_Model_Quote_Address $address
	 * @return Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge
	 */
	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		if ($address->getPaymentSurchargeAmount()) {
			$address->addTotal(array(
				'code' => $this->getCode(),
				'title' => $this->getLabel(),
				'value' => $address->getPaymentSurchargeAmount()
			));
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->_helper()->__('Payment Surcharge');
	}
```

#### Invoice total model
The invoice and credit memo total models basically have the same logic. Note the different abstract classes.
```php
class Sfrost2004_PaymentSurcharge_Model_Invoice_Payment_Surcharge extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	/**
	 * Update Invoice to add our custom total
	 * 
	 * @param Mage_Sales_Model_Order_Invoice $invoice
	 *
	 * @return $this
	 */
	public function collect(Mage_Sales_Model_Order_Invoice $invoice)
	{
		// This surcharge calculation logic would usually be in a Helper
		$surchargeRate = 4;
		$surcharge      = ($invoice->getSubtotal() / 100) * $surchargeRate;
		$baseSurcharge  = ($invoice->getBaseSubtotal() / 100) * $surchargeRate;
		
		$invoice->setPaymentSurchargeAmount($surcharge)
		       ->setBasePaymentSurchargeAmount($baseSurcharge)
		       ->setGrandTotal($invoice->getGrandTotal() + $surcharge)
		       ->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseSurcharge);

		return $this;
	}
}
```

#### Credit memo total model
```php
class Sfrost2004_PaymentSurcharge_Model_Creditmemo_Payment_Surcharge extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	/**
	 * Update credit memo to add our custom total
	 *
	 * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
	 *
	 * @return $this
	 */
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
	{
		// This surcharge calculation logic would usually be in a Helper
		$surchargeRate = 4;
		$surcharge      = ( $creditmemo->getSubtotal() / 100) * $surchargeRate;
		$baseSurcharge  = ( $creditmemo->getBaseSubtotal() / 100) * $surchargeRate;

		$creditmemo->setPaymentSurchargeAmount($surcharge)
		           ->setBasePaymentSurchargeAmount($baseSurcharge)
		           ->setGrandTotal( $creditmemo->getGrandTotal() + $surcharge)
		           ->setBaseGrandTotal( $creditmemo->getBaseGrandTotal() + $baseSurcharge);

		return $this;
	}
}
```

### Add the surcharge attribute
Now let's add an attribute to these entities to save the surcharge amount:

```php
/* @var $installer Mage_Sales_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

foreach (['order', 'invoice', 'creditmemo'] as $entityType) {
	$installer->addAttribute($entityType, 'payment_surcharge_amount', ['type' => 'decimal']);
	$installer->addAttribute($entityType, 'base_payment_surcharge_amount', ['type' => 'decimal']);
}

$installer->endSetup();
```
Make sure the `class` node of the `resources/sfrost2004_paymentsurcharge/setup` node is set to `Mage_Sales_Model_Resource_Setup` so we have access to the `addAttribute` method.

### Add fieldsets to copy value from quote to order
As it says:
```xml
    <global>
        <fieldsets>
            <sales_convert_quote_address>
                <payment_surcharge_amount>
                    <to_order>*</to_order>
                </payment_surcharge_amount>
                <base_payment_surcharge_amount>
                    <to_order>*</to_order>
                </base_payment_surcharge_amount>
            </sales_convert_quote_address>
        </fieldsets>
```

### Add total to total blocks
This block class will add our custom total to the total block.
```php
class Sfrost2004_PaymentSurcharge_Block_Sales_Total_Payment_Surcharge extends Mage_Core_Block_Abstract
{
	/**
	 * Add our custom total to the parent totals block
	 * 
	 * Called by Mage_Sales_Block_Order_Totals::_beforeToHtml()
	 *
	 * @return Sfrost2004_PaymentSurcharge_Block_Sales_Total_Payment_Surcharge
	 */
	public function initTotals()
	{
		$parent = $this->getParentBlock();
		$value = $parent->getSource()->getPaymentSurchargeAmount();

		if ($value > 0.01 || $value < -0.01) {
			$total = new Varien_Object(array(
				'code' => 'payment_surcharge',
				'value' => $value,
				'base_value' => $parent->getSource()->getBasePaymentSurchargeAmount(),
				'label' => $this->__('Payment Surcharge'),
				'field' => 'payment_surcharge'
			));
			// Add total to parent block after the shipping total
			$parent->addTotal($total, 'shipping');
		}
		return $this;
	}
}

```

### Add frontend and adminhtml layout updates

These will update the totals block to add our custom surcharge total.

#### Frontend layout updates

These updates update the totals block wherever it appears in the frontend - order, invoice, credit memo, print views and sales emails.
```xml
    <layout version="0.1.0">
    	
    	<sales_order_view>
    		<reference name="order_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_view>
    
    	<sales_order_invoice>
    		<reference name="invoice_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_invoice>
    
    	<sales_order_creditmemo>
    		<reference name="creditmemo_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_creditmemo>
    
    	<sales_order_print>
    		<reference name="order_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_print>
    
    	<sales_order_printinvoice>
    		<reference name="invoice_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_printinvoice>
    
    	<sales_order_printcreditmemo>
    		<reference name="creditmemo_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_order_printcreditmemo>
    
    	<sales_email_order_items>
    		<reference name="order_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_email_order_items>
    
    	<sales_email_order_invoice_items>
    		<reference name="invoice_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_email_order_invoice_items>
    
    	<sales_email_order_creditmemo_items>
    		<reference name="creditmemo_totals">
    			<block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge" as="payment_surcharge"/>
    		</reference>
    	</sales_email_order_creditmemo_items>
    	
    </layout>
```
#### Adminhtml layout updates

These updates update the totals block wherever it appears in the admin - view order, view/create new invoice, update invoice quantity and view/create new credit memo.

```xml
<layout version="0.1.0">

    <adminhtml_sales_order_view>
        <reference name="order_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_view>

    <adminhtml_sales_order_invoice_new>
        <reference name="invoice_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_invoice_new>

    <adminhtml_sales_order_invoice_updateqty>
        <reference name="invoice_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_invoice_updateqty>

    <adminhtml_sales_order_invoice_view>
        <reference name="invoice_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_invoice_view>

    <adminhtml_sales_order_creditmemo_new>
        <reference name="creditmemo_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_creditmemo_new>

    <adminhtml_sales_order_creditmemo_view>
        <reference name="creditmemo_totals">
            <block type="sfrost2004_paymentsurcharge/sales_total_payment_surcharge" name="total_payment_surcharge"
                   as="payment_surcharge"/>
        </reference>
    </adminhtml_sales_order_creditmemo_view>

</layout>

```


Requirements
------------
- PHP >= 5.2.0
- Mage_Core

Compatibility
-------------
- Tested on Magento 1.9.3.1

Installation Instructions
-------------------------
1. Install the extension by copying all the files into your document root.
2. Clear the cache, logout from the admin panel and then login again.
3. Configure and activate the extension under System - Configuration - Company - Example Extension.

Uninstallation
--------------
1. Remove all extension files from your Magento installation

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/sfrost2004/Sfrost2004_PaymentSurcharge/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------

[http://www.frostnet.co.uk](http://www.frostnet.co.uk)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2016 Sfrost2004
