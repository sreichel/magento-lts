<?php

/**
 * @category   Mage
 * @package    Mage_Bundle
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();
$installer->updateAttribute('catalog_product', 'price_view', 'used_in_product_listing', 1);
$installer->updateAttribute('catalog_product', 'shipment_type', 'used_in_product_listing', 1);
$installer->updateAttribute('catalog_product', 'weight_type', 'used_in_product_listing', 1);
$installer->endSetup();
