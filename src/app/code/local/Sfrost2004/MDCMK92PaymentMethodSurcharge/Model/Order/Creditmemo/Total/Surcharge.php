<?php

/**
 * Order credit memo surcharge total calculation model
 *
 */
class Sfrost2004_MDCMK92PaymentMethodSurcharge_Model_Order_Creditmemo_Total_Surcharge
	extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
//        $orderSurcharge        = $creditmemo->getOrder()->getSurcharge();
//        $baseOrderSurcharge    = $creditmemo->getOrder()->getBaseSurcharge();
//        if ($orderSurcharge) {
//            /**
//             * Check surcharge amount in previous invoices
//             */
//            foreach ($creditmemo->getOrder()->getInvoiceCollection() as $previousInvoice) {
//                if ($previousInvoice->getSurcharge() && !$previousInvoice->isCanceled()) {
//                    return $this;
//                }
//            }
//            $creditmemo->setSurcharge($orderSurcharge);
//            $creditmemo->setBaseSurcharge($baseOrderSurcharge);
//
//            $creditmemo->setGrandTotal( $creditmemo->getGrandTotal() + $orderSurcharge);
//            $creditmemo->setBaseGrandTotal( $creditmemo->getBaseGrandTotal() + $baseOrderSurcharge);
//        }
//        return $this;

	    $helper = Mage::helper('sfrost2004_mdcmk92paymentmethodsurcharge');

	    if(!$helper->isExtensionEnabled()) {
		    return $this;
	    }

	    $surcharge        = $creditmemo->getOrder()->getSurcharge();
	    $baseSurcharge    = $creditmemo->getOrder()->getBaseSurcharge();

	    if($surcharge) {
		    $creditmemo->setPaymentSurchargeAmount($surcharge)
		           ->setBasePaymentSurchargeAmount($baseSurcharge)
		           ->setGrandTotal($creditmemo->getGrandTotal() + $surcharge)
		           ->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseSurcharge);
	    }

	    return $this;
    }


}
