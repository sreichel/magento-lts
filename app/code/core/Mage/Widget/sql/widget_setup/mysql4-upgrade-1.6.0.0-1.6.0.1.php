<?php

/**
 * This file is part of OpenMage.
 * For copyright and license information, please view the COPYING.txt file that was distributed with this source code.
 *
 * @category   Mage
 * @package    Mage_Widget
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->changeColumn(
    $installer->getTable('widget/widget_instance_page'),
    'page_group',
    'page_group',
    [
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
    ],
);

$installer->getConnection()->changeColumn(
    $installer->getTable('widget/widget_instance_page'),
    'page_for',
    'page_for',
    [
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 255,
    ],
);
