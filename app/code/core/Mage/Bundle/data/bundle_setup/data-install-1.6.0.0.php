<?php

/**
 * @category   Mage
 * @package    Mage_Bundle
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;

$fieldList = [
    'price','special_price','special_from_date','special_to_date',
    'minimal_price','cost','tier_price','weight',
];
foreach ($fieldList as $field) {
    $applyTo = explode(',', $installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, $field, 'apply_to'));
    if (!in_array('bundle', $applyTo)) {
        $applyTo[] = 'bundle';
        $installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, $field, 'apply_to', implode(',', $applyTo));
    }
}

$applyTo = explode(',', $installer->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'cost', 'apply_to'));
unset($applyTo[array_search('bundle', $applyTo)]);
$installer->updateAttribute(Mage_Catalog_Model_Product::ENTITY, 'cost', 'apply_to', implode(',', $applyTo));
