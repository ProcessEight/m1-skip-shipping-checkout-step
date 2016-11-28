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
 * @package     Default (Template) Project
 * @copyright   Copyright (c) 2016 Sfrost2004
 * @author      Sfrost2004
 *
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'surcharge', 'decimal(12,4) default NULL AFTER `base_subtotal_with_discount`');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'surcharge_refunded', 'decimal(12,4) default NULL AFTER `surcharge`');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'surcharge_invoiced', 'decimal(12,4) default NULL AFTER `surcharge_refunded`');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'base_surcharge', 'decimal(12,4) default NULL AFTER `surcharge_invoiced`');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'base_surcharge_refunded', 'decimal(12,4) default NULL AFTER `base_surcharge`');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'), 'base_surcharge_invoiced', 'decimal(12,4) default NULL AFTER `base_surcharge_refunded`');

$installer->endSetup();