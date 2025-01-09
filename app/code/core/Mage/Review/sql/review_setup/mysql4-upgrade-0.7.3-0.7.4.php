<?php

/**
 * @category   Mage
 * @package    Mage_Review
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addConstraint(
    'FK_REVIEW_STORE_REVIEW',
    $installer->getTable('review/review_store'),
    'review_id',
    $installer->getTable('review/review'),
    'review_id',
    'CASCADE',
    'CASCADE',
    true,
);

$installer->endSetup();
