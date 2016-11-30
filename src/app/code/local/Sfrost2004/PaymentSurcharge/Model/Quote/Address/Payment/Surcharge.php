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

class Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
	/**
	 * Calculate surcharge amount
	 *
	 * @param Mage_Sales_Model_Quote_Address $address
	 * @return Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge
	 */
	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		parent::collect($address);

		$this->_setAmount(0);
		$this->_setBaseAmount(0);

		$surchargeRate = 4;

		// You would add a check to see if the surcharge should be applied here
		$store = $address->getQuote()->getStore();
		// This surcharge calculation logic would usually be in a Helper
		$surcharge      = ($address->getSubtotal() / 100) * $surchargeRate;
		$baseSurcharge  = ($address->getBaseSubtotal() / 100) * $surchargeRate;
		if ($surcharge) {
			$this->_setAmount($surcharge);
			$this->_setBaseAmount($baseSurcharge);
		}

		return $this;
	}

	/**
	 * Set values for display on address model
	 *
	 * @param Mage_Sales_Model_Quote_Address $address
	 * @return Sfrost2004_PaymentSurcharge_Model_Quote_Address_Payment_Surcharge
	 */
	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		if ($address->getPaymentSurchargeAmount()) {
			$address->addTotal(array(
				'code' => $this->getCode(),
				'title' => $this->getLabel(),
				'value' => $address->getPaymentSurchargeAmount()
			));
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->_helper()->__('Payment Surcharge');
	}

	/**
	 * @return Sfrost2004_PaymentSurcharge_Helper_Data
	 */
	protected function _helper()
	{
		return Mage::helper('sfrost2004_paymentsurcharge');
	}

	/**
	 * @param Mage_Sales_Model_Quote $quote
	 * @return Mage_Payment_Model_Method_Abstract
	 */
	protected function _getQuotePaymentMethodCode($quote)
	{
		if ($quote && $quote->getPayment() && $quote->getPayment()->getMethod())
		{
			return $quote->getPayment()->getMethod();
		}
		return false;
	}
}
