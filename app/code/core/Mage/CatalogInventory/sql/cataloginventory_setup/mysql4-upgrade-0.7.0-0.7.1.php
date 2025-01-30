<?php

/**
 * @category   Mage
 * @package    Mage_CatalogInventory
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'low_stock_date', 'datetime');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'notify_stock_qty', 'decimal(12,4)');
$installer->getConnection()->addColumn($this->getTable('cataloginventory_stock_item'), 'use_config_notify_stock_qty', "tinyint(1) unsigned NOT NULL default '1'");

$installer->endSetup();
