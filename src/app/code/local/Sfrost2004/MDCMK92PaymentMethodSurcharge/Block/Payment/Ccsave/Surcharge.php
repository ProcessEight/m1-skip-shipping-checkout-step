<?php

/**
 * Ccsave payment form surcharge block
 */
class Sfrost2004_MDCMK92PaymentMethodSurcharge_Block_Payment_Ccsave_Surcharge extends Mage_Core_Block_Template
{
	/**
	 * Set custom template with surcharge message
	 */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sfrost2004/mdcmk92paymentmethodsurcharge/payment/ccsave/surcharge.phtml');
    }

    /**
     * Return code of payment method
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getMethod()->getCode();
    }
}
