<?php

/**
 * Tax totals modification block. Can be used just as subblock of Mage_Sales_Block_Order_Totals
 */
class Sfrost2004_MDCMK92PaymentMethodSurcharge_Block_Sales_Order_Surcharge extends Mage_Core_Block_Template
{
    /**
     * Initialize all order totals related with surcharge
     *
     * @return Sfrost2004_MDCMK92PaymentMethodSurcharge_Block_Sales_Order_Surcharge
     */
    public function initTotals()
    {
        /** @var $parent Mage_Adminhtml_Block_Sales_Order_Invoice_Totals */
        $parent = $this->getParentBlock();

	    $surchargeTotal = new Varien_Object(array(
		    'code'  => 'surcharge',
		    'value' => $parent->getSource()->getSurcharge(),
		    'label' => $this->__('Surcharge')
	    ));
	    $parent->addTotalBefore($surchargeTotal, 'shipping');

        return $this;
    }

	/**
	 * @return mixed
	 */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

	/**
	 * @return mixed
	 */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
}
