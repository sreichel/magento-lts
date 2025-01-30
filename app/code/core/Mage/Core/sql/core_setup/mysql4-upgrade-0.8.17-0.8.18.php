<?php

/**
 * @category   Mage
 * @package    Mage_Core
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('core_email_variable'),
    'is_html',
    "tinyint(1) NOT NULL DEFAULT '0'",
);
$installer->getConnection()->changeColumn(
    $installer->getTable('core_email_variable_value'),
    'value',
    'value',
    'TEXT NOT NULL',
);

$installer->endSetup();
