<?php

class Sfrost2004_MDCMK92PaymentMethodSurcharge_Block_Sales_Total_Payment_Surcharge extends Mage_Core_Block_Abstract
{
	/**
	 * Set values to display on parent block.
	 *
	 * Called by Mage_Sales_Block_Order_Totals::_beforeToHtml()
	 *
	 * @return Sfrost2004_MDCMK92PaymentMethodSurcharge_Block_Sales_Total_Payment_Surcharge
	 */
	public function initTotals()
	{
		$parent = $this->getParentBlock();
		$value = $parent->getSource()->getSurcharge();

		if ($value > 0.01 || $value < -0.01) {
			$total = new Varien_Object(array(
				'code' => 'surcharge',
				'value' => $value,
				'base_value' => $parent->getSource()->getBaseSurcharge(),
				'label' => $this->__('Surcharge'),
				'field' => 'surcharge'
			));
			$parent->addTotal($total, 'shipping');
		}
		return $this;
	}
}
