<?php
/**
 * Zone8
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact Zone8 for more information.
 *
 * @category    Zone8
 * @package     Default (Template) Project
 * @copyright   Copyright (c) 2016 Zone8
 * @author      Zone8
 *
 */ 
class Sfrost2004_MDCMK92PaymentMethodSurcharge_Helper_Data extends Mage_Core_Helper_Abstract
{
	const EXTENSION_ENABLED_XML = 'mdcmk92paymentmethodsurcharge/general/enabled';
	const EXTENSION_SURCHARGE_XML = 'payment/ccsave/surcharge';

	public function isExtensionEnabled() {
		return Mage::getStoreConfigFlag(self::EXTENSION_ENABLED_XML);
	}

	/**
	 * Return centinel block for payment form with logos
	 *
	 * @param Mage_Payment_Model_Method_Abstract $method
	 * @return Mage_Centinel_Block_Logo
	 */
	public function getMethodFormBlock($method)
	{
		$blockType = 'sfrost2004_mdcmk92paymentmethodsurcharge/payment_ccsave_surcharge';
		if ($this->getLayout()) {
			$block = $this->getLayout()->createBlock($blockType);
		}
		else {
			$className = Mage::getConfig()->getBlockClassName($blockType);
			$block = new $className;
		}
		$block->setMethod($method);
		return $block;
	}

	/**
	 * @return int|float
	 */
	public function getSurcharge()
	{
		return Mage::getStoreConfig(self::EXTENSION_SURCHARGE_XML);
	}
}