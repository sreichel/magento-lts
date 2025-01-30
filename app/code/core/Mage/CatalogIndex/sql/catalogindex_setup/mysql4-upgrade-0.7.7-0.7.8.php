<?php

/**
 * @category   Mage
 * @package    Mage_CatalogIndex
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/** @var Mage_CatalogIndex_Model_Resource_Setup $installer */
$installer  = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('catalogindex_price'), 'website_id', 'smallint(5) unsigned');
$installer->getConnection()->addColumn($installer->getTable('catalogindex_minimal_price'), 'website_id', 'smallint(5) unsigned');

$installer->convertStoreToWebsite($installer->getTable('catalogindex_minimal_price'));
$installer->convertStoreToWebsite($installer->getTable('catalogindex_price'));

$installer->endSetup();
