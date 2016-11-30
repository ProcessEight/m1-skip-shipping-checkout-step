<?php

class Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Quote_Total_Surcharge extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('surcharge');
    }

    /**
     * Collect totals information about surcharge
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Quote_Total_Surcharge
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
    	$helper = Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge');

	    if(!$helper->isExtensionEnabled()) {
	        return $this;
	    }

        parent::collect($address);

	    $this->_setAmount(0);
	    $this->_setBaseAmount(0);

	    $surchargeRate  = $helper->getSurcharge();
	    $subtotal       = $address->getSubtotal();
	    $baseSubtotal   = $address->getBaseSubtotal();

	    if($surchargeRate) {
		    $surcharge      = ($subtotal / 100) * $surchargeRate;
		    $baseSurcharge  = ($baseSubtotal / 100) * $surchargeRate;

		    $this->_setAmount($surcharge);
		    $this->_setBaseAmount($baseSurcharge);
	    }

        return $this;

//	    parent::collect($address);
//
//	    $this->_setAmount(0)->_setBaseAmount(0);
//
//	    $payment = $this->_getQuotePaymentMethodCode($address->getQuote());
//	    if ($payment) {
//		    $store = $address->getQuote()->getStore();
//		    $surcharge = $this->_helper()->getSurchargeAmount($payment, $address->getSubtotal(), $store);
//		    $baseSurcharge = $this->_helper()->getBaseSurchargeAmount($payment, $address->getBaseSubtotal(), $store);
//		    if ($surcharge) {
//			    $this->_setAmount($surcharge)->_setBaseAmount($baseSurcharge);
//		    }
//	    }
//
//	    return $this;
    }

    /**
     * Add shipping totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Quote_Total_Surcharge
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getTotalAmount('surcharge');
        if ($amount != 0 || $address->getPaymentMethod() == "ccsave") {
            $title = Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge')->__('Credit Card Surcharge');
//            if ($address->getShippingDescription()) {
//                $title .= ' (' . $address->getShippingDescription() . ')';
//            }
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => $title,
                'value' => $address->getTotalAmount('surcharge')
            ));
        }
        return $this;
    }

    /**
     * Get surcharge label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge')->__('Surcharge');
    }
}
