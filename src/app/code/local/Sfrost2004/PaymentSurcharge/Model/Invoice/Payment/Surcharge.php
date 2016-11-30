<?php
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
