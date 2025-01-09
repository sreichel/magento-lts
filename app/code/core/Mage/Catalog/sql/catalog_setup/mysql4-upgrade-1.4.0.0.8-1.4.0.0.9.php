<?php

/**
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('catalog/product_index_price'),
    'final_price',
    'DECIMAL(12,4) DEFAULT NULL AFTER `price`',
);
$installer->getConnection()->addColumn(
    $installer->getTable('catalog/product_index_price'),
    'tier_price',
    'DECIMAL(12,4) DEFAULT NULL',
);

$installer->endSetup();
