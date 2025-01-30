<?php

/**
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('catalog/product_option'), 'image_size_x', 'smallint unsigned not null after `file_extension`');
$installer->getConnection()->addColumn($installer->getTable('catalog/product_option'), 'image_size_y', 'smallint unsigned not null after `image_size_x`');

$installer->endSetup();
