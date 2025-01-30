<?php

/**
 * @category   Mage
 * @package    Mage_CatalogRule
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('catalogrule'), 'simple_action', 'varchar(32) not null');
$installer->getConnection()->addColumn($this->getTable('catalogrule'), 'discount_amount', 'decimal(12,4) not null');

$installer->endSetup();
