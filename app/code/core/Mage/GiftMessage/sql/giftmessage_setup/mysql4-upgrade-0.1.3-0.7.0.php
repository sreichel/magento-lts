<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_GiftMessage
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$this->startSetup()
    ->addAttribute('quote', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('quote_address', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('quote_item', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('quote_address_item', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('order', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('order_item', 'gift_message_id', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('order_item', 'gift_message_available', ['type' => 'int', 'visible' => false, 'required' => false])
    ->addAttribute('catalog_product', 'gift_message_available', [
        'backend'       => 'giftmessage/entity_attribute_backend_boolean_config',
        'frontend'      => '',
        'label'         => 'Allow Gift Message',
        'input'         => 'select',
        'class'         => '',
        'source'        => 'giftmessage/entity_attribute_source_boolean_config',
        'global'        => true,
        'visible'       => true,
        'required'      => false,
        'user_defined'  => false,
        'default'       => '2',
        'visible_on_front' => false,
    ])
    ->removeAttribute('catalog_product', 'gift_message_aviable')
    ->setConfigData('sales/gift_messages/allow', 1)
    ->endSetup();
