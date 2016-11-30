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
 * @package     PaymentSurcharge
 * @copyright   Copyright (c) 2016 Sfrost2004
 * @author      Sfrost2004
 *
 */

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
