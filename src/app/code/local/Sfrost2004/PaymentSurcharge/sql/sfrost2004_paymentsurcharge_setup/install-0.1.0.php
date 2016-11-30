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

/* @var $installer Mage_Sales_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

foreach (['order', 'invoice', 'creditmemo'] as $entityType) {
	$installer->addAttribute($entityType, 'payment_surcharge_amount', ['type' => 'decimal']);
	$installer->addAttribute($entityType, 'base_payment_surcharge_amount', ['type' => 'decimal']);
}

$installer->endSetup();
