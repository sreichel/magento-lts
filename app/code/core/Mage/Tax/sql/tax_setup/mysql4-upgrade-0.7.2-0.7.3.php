<?php

/**
 * @category   Mage
 * @package    Mage_Tax
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

if (!$installer->getConnection()->fetchOne("select * from {$this->getTable('tax_class')} where `class_name`='Shipping' and `class_type`='PRODUCT'")) {
    $installer->run("
        insert  into {$this->getTable('tax_class')} (`class_name`,`class_type`) values ('Shipping','PRODUCT');
    ");
}

$installer->endSetup();
