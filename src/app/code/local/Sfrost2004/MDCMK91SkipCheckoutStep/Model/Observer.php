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

class Sfrost2004_MDCMK91SkipCheckoutStep_Model_Observer extends Varien_Event_Observer
{
	/**
	 * Set templates to add our custom JavaScript
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function setTemplate(Varien_Event_Observer $observer)
	{
		/** @var Mage_Core_Model_Layout $layout */
		$layout = $observer->getEvent()->getLayout();

		/** @var Mage_Core_Block_Template $block */
		$block = $layout->getBlock("checkout.onepage");
		if($block) {
			$block->setTemplate("sfrost2004/checkout/onepage.phtml");
		}

		$block = $layout->getBlock("checkout.onepage.payment");
		if($block) {
			$block->setTemplate("sfrost2004/checkout/onepage/payment.phtml");
		}
	}

	/**
	 * Set template for shipping method progress step (to remove link to step)
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function setProgressTemplate(Varien_Event_Observer $observer)
	{
		/** @var Mage_Core_Model_Layout $layout */
		$block = $observer->getEvent()->getBlock();

		/** @var Mage_Core_Block_Template $block */
		if($block instanceof Mage_Checkout_Block_Onepage_Progress
			&& current($block->getLayout()->getUpdate()->getHandles()) == "checkout_onepage_progress_shipping_method") {
			$block->setTemplate("sfrost2004/checkout/onepage/progress/shipping_method.phtml");
		}
	}
}