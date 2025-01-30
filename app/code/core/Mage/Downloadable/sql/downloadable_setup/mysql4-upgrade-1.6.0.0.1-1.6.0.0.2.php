<?php

/**
 * @category   Mage
 * @package    Mage_Downloadable
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installFile = __DIR__ . DS . 'upgrade-1.6.0.0.1-1.6.0.0.2.php';
if (file_exists($installFile)) {
    include $installFile;
}

/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;

/** @var Varien_Db_Adapter_Pdo_Mysql $connection */
$connection = $installer->getConnection();

$connection->changeTableEngine(
    $installer->getTable('downloadable/product_price_indexer_tmp'),
    Varien_Db_Adapter_Pdo_Mysql::ENGINE_MEMORY,
);
