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

$table = $this->getTable('catalog/category_product_index');

/**
 * Remove data duplicates
 */
$stmt = $installer->getConnection()->query(
    'SELECT * FROM ' . $table . ' GROUP BY category_id, product_id, store_id HAVING count(*)>1',
);

while ($row = $stmt->fetch()) {
    $condition = 'category_id=' . $row['category_id']
        . ' AND product_id=' . $row['product_id']
        . ' AND store_id=' . $row['store_id'] . ' AND is_parent=0';
    $installer->getConnection()->delete($table, $condition);
}

$installer->getConnection()->addKey(
    $table,
    'UNQ_CATEGORY_PRODUCT',
    ['category_id', 'product_id', 'store_id'],
    'unique',
);
