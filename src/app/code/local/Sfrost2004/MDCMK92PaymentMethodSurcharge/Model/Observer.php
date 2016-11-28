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

class Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Observer extends Varien_Event_Observer
{
	/**
	 * @param Varien_Event_Observer $observer
	 *
	 * @return $this
	 */
	public function addSurchargeToPaymentMethodName(Varien_Event_Observer $observer)
	{
		$paymentFormBlock   = $observer->getEvent()->getBlock();
		$method             = $paymentFormBlock->getMethod();

		$helper = Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge');

		if ($method && $helper->isExtensionEnabled()) {
			$paymentFormBlock->setChild(
				'payment.method.' . $method->getCode() . '',
				$helper->getMethodFormBlock($method)
			);
		}
		return $this;
	}
}