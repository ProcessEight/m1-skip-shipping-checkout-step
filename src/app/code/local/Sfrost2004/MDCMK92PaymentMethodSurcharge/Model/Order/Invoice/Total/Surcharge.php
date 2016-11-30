<?php

/**
 * Order invoice surcharge total calculation model
 *
 */
class Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Order_Invoice_Total_Surcharge
	extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
	    $helper = Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge');

	    if(!$helper->isExtensionEnabled()) {
		    return $this;
	    }

        $orderSurcharge        = $invoice->getOrder()->getSurcharge();
        $baseOrderSurcharge    = $invoice->getOrder()->getBaseSurcharge();
        if ($orderSurcharge) {
            $invoice->setSurcharge($orderSurcharge);
            $invoice->setBaseSurcharge($baseOrderSurcharge);
            $invoice->setGrandTotal($invoice->getGrandTotal()+$orderSurcharge);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal()+$baseOrderSurcharge);
        }
        return $this;

	    /* @var $order Mage_Sales_Model_Order */
//	    $order = $object->getOrder();
//	    $store = $order->getStore();
//	    $payment = $order->getPayment()->getMethod();
//
//	    $surcharge = $this->getSurchargeAmount($payment, $object->getSubtotal(), $store);
//	    $baseSurcharge = $this->getSurchargeAmount($payment, $object->getBaseSubtotal(), $store);
//
//	    $object->setPaymentSurchargeAmount($surcharge)
//	           ->setBasePaymentSurchargeAmount($baseSurcharge)
//	           ->setGrandTotal($object->getGrandTotal() + $surcharge)
//	           ->setBaseGrandTotal($object->getBaseGrandTotal() + $baseSurcharge);
//
//	    return $this;
    }


}
