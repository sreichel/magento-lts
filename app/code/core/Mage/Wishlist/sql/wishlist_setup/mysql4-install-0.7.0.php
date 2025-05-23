<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Wishlist
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('wishlist')};
CREATE TABLE {$this->getTable('wishlist')} (
  `wishlist_id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(10) unsigned NOT NULL default '0',
  `shared` tinyint(1) unsigned default '0',
  `sharing_code` varchar(32) character set latin1 collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`wishlist_id`),
  UNIQUE KEY `FK_CUSTOMER` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Wishlist main';

-- DROP TABLE IF EXISTS {$this->getTable('wishlist_item')};
CREATE TABLE {$this->getTable('wishlist_item')} (
  `wishlist_item_id` int(10) unsigned NOT NULL auto_increment,
  `wishlist_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `store_id` int(10) unsigned NOT NULL default '0',
  `added_at` datetime default NULL,
  `description` text,
  PRIMARY KEY  (`wishlist_item_id`),
  KEY `FK_ITEM_WISHLIST` (`wishlist_id`),
  KEY `FK_WISHLIST_PRODUCT` (`product_id`),
  KEY `FK_WISHLIST_STORE` (`store_id`),
  CONSTRAINT `FK_ITEM_WISHLIST` FOREIGN KEY (`wishlist_id`) REFERENCES {$this->getTable('wishlist')} (`wishlist_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Wishlist items';

    ");

$installer->endSetup();
