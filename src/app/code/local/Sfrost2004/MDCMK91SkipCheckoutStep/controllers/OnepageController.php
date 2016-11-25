<?php
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'OnepageController.php';

/**
 * Onepage controller for checkout
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Sfrost2004_MDCMK91SkipCheckoutStep_OnepageController extends Mage_Checkout_OnepageController
{
	/**
	 * Save checkout billing address
	 */
	public function saveBillingAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost('billing', array());
			$customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

			if (isset($data['email'])) {
				$data['email'] = trim($data['email']);
			}
			$result = $this->getOnepage()->saveBilling($data, $customerAddressId);

			if (!isset($result['error'])) {
				if ($this->getOnepage()->getQuote()->isVirtual()) {
					$result['goto_section'] = 'payment';
					$result['update_section'] = array(
						'name' => 'payment-method',
						'html' => $this->_getPaymentMethodsHtml()
					);
				} elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {

					if($this->_isSkipCheckoutStepEnabled()) {

						// Get shipping methods for basket
						$rates = Mage::getModel('sfrost2004_mdcmk91skipcheckoutstep/shipping_method')->getShippingRates();

						if(count($rates) == 1) {
							// If there is only one, set it as the chosen method
							foreach ( $rates as $rate ) {
								$shippingMethodCode = $rate[0]->getCode();
								$result = $this->getOnepage()->saveShippingMethod($shippingMethodCode);
								break;
							}
							// $result will contain error data if shipping method is empty
							if(!$result) {
								Mage::dispatchEvent(
									'checkout_controller_onepage_save_shipping_method',
									array(
										'request'   => $this->getRequest(),
						                'quote'     => $this->getOnepage()->getQuote(),
									)
								);
								$this->getOnepage()->getQuote()->collectTotals();
								$this->_prepareDataJSON($result);

								// Jump shipping address and shipping methods steps and go straight to payment step
								$result['goto_section'] = 'payment';
								$result['update_section'] = array(
									'name' => 'payment-method',
									'html' => $this->_getPaymentMethodsHtml()
								);
							}
							$this->getOnepage()->getQuote()->collectTotals()->save();

						} else {
							$result['goto_section'] = 'shipping_method';
							$result['update_section'] = array(
								'name' => 'shipping-method',
								'html' => $this->_getShippingMethodsHtml()
							);
							$result['allow_sections'] = array('shipping');
						}
					} else {
						$result['goto_section'] = 'shipping_method';
						$result['update_section'] = array(
							'name' => 'shipping-method',
							'html' => $this->_getShippingMethodsHtml()
						);
						$result['allow_sections'] = array('shipping');
					}

					$result['duplicateBillingInfo'] = 'true';
				} else {
					$result['goto_section'] = 'shipping';
				}
			}

			$this->_prepareDataJSON($result);
		}
	}

	/**
	 * Shipping address save action
	 */
	public function saveShippingAction()
	{
		if ($this->_expireAjax()) {
			return;
		}
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost('shipping', array());
			$customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
			$result = $this->getOnepage()->saveShipping($data, $customerAddressId);

			if (!isset($result['error'])) {

				if($this->_isSkipCheckoutStepEnabled()) {

					// Get shipping methods for basket
					$rates = Mage::getModel('sfrost2004_mdcmk91skipcheckoutstep/shipping_method')->getShippingRates();

					// If there is only one, set it as the chosen method
					foreach ( $rates as $rate ) {
						$shippingMethodCode = $rate[0]->getCode();
						$result = $this->getOnepage()->saveShippingMethod($shippingMethodCode);
						break;
					}

					// $result will contain error data if shipping method is empty
					if(!$result) {
						Mage::dispatchEvent(
							'checkout_controller_onepage_save_shipping_method',
							array(
								'request'   => $this->getRequest(),
								'quote'     => $this->getOnepage()->getQuote(),
							)
						);
						$this->getOnepage()->getQuote()->collectTotals();

						// Proceed straight to payment step
						$result['goto_section'] = 'payment';
						$result['update_section'] = array(
							'name' => 'payment-method',
							'html' => $this->_getPaymentMethodsHtml()
						);
					}
					$this->getOnepage()->getQuote()->collectTotals()->save();
				} else {

					$result['goto_section'] = 'shipping_method';
					$result['update_section'] = array(
						'name' => 'shipping-method',
						'html' => $this->_getShippingMethodsHtml()
					);
				}
			}
			$this->_prepareDataJSON($result);
		}
	}

	/**
	 * @return bool
	 */
	protected function _isSkipCheckoutStepEnabled()
	{
		return Mage::helper('sfrost2004_mdcmk91skipcheckoutstep')->isExtensionEnabled();
	}
}
