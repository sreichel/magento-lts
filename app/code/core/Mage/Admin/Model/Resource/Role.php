<?php

/**
 * @category   Mage
 * @package    Mage_Admin
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Admin role resource model
 *
 * @category   Mage
 * @package    Mage_Admin
 */
class Mage_Admin_Model_Resource_Role extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('admin/role', 'role_id');
    }

    /**
     * Process role before saving
     *
     * @return $this
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreated($this->formatDate(true));
        }
        $object->setModified($this->formatDate(true));
        return $this;
    }
}
