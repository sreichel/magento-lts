<?php

/**
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Sales_Model_Entity_Setup $installer */
$installer = $this;

$installer->addAttribute('quote', 'customer_prefix', ['type' => 'static']);
$installer->addAttribute('quote', 'customer_middlename', ['type' => 'static']);
$installer->addAttribute('quote', 'customer_suffix', ['type' => 'static']);

$installer->addAttribute('quote_address', 'prefix', ['type' => 'static']);
$installer->addAttribute('quote_address', 'middlename', ['type' => 'static']);
$installer->addAttribute('quote_address', 'suffix', ['type' => 'static']);
