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
