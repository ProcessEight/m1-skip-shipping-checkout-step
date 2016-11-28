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

	    $paymentMethod = $address->getPaymentMethod();
	    if($paymentMethod != "ccsave") {
	        return $this;
	    }

        parent::collect($address);

	    $surchargeRate  = $helper->getSurcharge();
	    $baseSubtotal   = $address->getBaseSubtotal();
	    $baseSurcharge  = 0;

	    $baseSurcharge = ($baseSubtotal / 100) * $surchargeRate;

	    $amountPrice = $address->getQuote()->getStore()->convertPrice($baseSurcharge, false);

	    $this->_setAmount($amountPrice);
	    $this->_setBaseAmount($baseSurcharge);

	    $address->setSurcharge($amountPrice);
	    $address->setBaseSurcharge($baseSurcharge);

        return $this;
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
