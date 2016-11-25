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
class Sfrost2004_MDCMK91SkipCheckoutStep_Helper_Data extends Mage_Core_Helper_Abstract
{
	const EXTENSION_ENABLED_XML = 'sfrost2004/general/enabled';

	public function isExtensionEnabled() {
		return Mage::getStoreConfigFlag(self::EXTENSION_ENABLED_XML);
	}
}