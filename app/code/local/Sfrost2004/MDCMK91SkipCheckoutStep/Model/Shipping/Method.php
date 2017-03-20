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
class Sfrost2004_MDCMK91SkipCheckoutStep_Model_Shipping_Method extends Mage_Core_Model_Abstract
{
	/**
	 * @return array
	 */
	public function getShippingRates()
	{
		/** @var Mage_Checkout_Block_Onepage_Shipping_Method_Available $block */
		$block = Mage::app()->getLayout()->createBlock('checkout/onepage_shipping_method_available');

		$rates = $block->getShippingRates();

		return $rates;
	}
}