<?php

/**
 * @category   Mage
 * @package    Mage_Dataflow
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Convert zend db adapter
 *
 * @category   Mage
 * @package    Mage_Dataflow
 */
class Mage_Dataflow_Model_Convert_Adapter_Zend_Db extends Mage_Dataflow_Model_Convert_Adapter_Abstract
{
    public function getResource()
    {
        if (!$this->_resource) {
            $this->_resource = Zend_Db::factory($this->getVar('adapter', 'Pdo_Mysql'), $this->getVars());
        }
        return $this->_resource;
    }

    public function load()
    {
        return $this;
    }

    public function save()
    {
        return $this;
    }
}
