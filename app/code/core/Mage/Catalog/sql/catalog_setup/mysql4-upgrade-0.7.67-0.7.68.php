<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Catalog_Model_Resource_Setup  $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('catalog/product'), 'required_options', 'tinyint(1) unsigned NOT NULL default 0');

$entityTypeId   = 'catalog_product';
$attributeId    = $installer->getAttributeId($entityTypeId, 'required_options');
$attributeTable = $installer->getAttributeTable($entityTypeId, $attributeId);

if ($attributeTable != $installer->getTable('catalog/product')) {
    $installer->run("
    UPDATE `{$installer->getTable('catalog/product')}` AS `p`
    INNER JOIN `{$attributeTable}` AS `a` ON `p`.`entity_id`=`a`.`entity_id`
        AND `a`.`attribute_id`={$attributeId} AND `a`.`store_id`=0
    SET `p`.`required_options` = `a`.`value`;
    ");

    $installer->updateAttribute($entityTypeId, $attributeId, 'backend_type', 'static');

    $installer->run("
    DELETE FROM `{$attributeTable}` WHERE `attribute_id`={$attributeId};
    ");
}

$installer->endSetup();
