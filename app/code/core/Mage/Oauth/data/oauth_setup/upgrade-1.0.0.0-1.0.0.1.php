<?php

/**
 * This file is part of OpenMage.
 * For copyright and license information, please view the COPYING.txt file that was distributed with this source code.
 *
 * @category   Mage
 * @package    Mage_Admin
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$table = $installer->getTable('admin/rule');
$resourceIds = [
    'admin/system/api/consumer' => 'admin/system/api/oauth_consumer',
    'admin/system/api/consumer/delete' => 'admin/system/api/oauth_consumer/delete',
    'admin/system/api/consumer/edit' => 'admin/system/api/oauth_consumer/edit',
    'admin/system/api/authorizedTokens' => 'admin/system/api/oauth_authorized_tokens',
];

foreach ($resourceIds as $oldId => $newId) {
    $installer->getConnection()->update(
        $table,
        ['resource_id' => $newId],
        ['resource_id = ?' => $oldId],
    );
}

$installer->endSetup();
