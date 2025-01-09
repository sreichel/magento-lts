<?php

/**
 * @category   Mage
 * @package    Mage_Weee
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Weee_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->addAttribute('quote_item', 'weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('quote_item', 'weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->addAttribute('order_item', 'weee_tax_applied_amount', ['type' => 'decimal']);
$installer->addAttribute('order_item', 'weee_tax_applied_row_amount', ['type' => 'decimal']);

$installer->endSetup();
