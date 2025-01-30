<?php

/**
 * @category   Mage
 * @package    Mage_Dataflow
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * DataFlow Import Model
 *
 * @category   Mage
 * @package    Mage_Dataflow
 *
 * @method Mage_Dataflow_Model_Resource_Import _getResource()
 * @method Mage_Dataflow_Model_Resource_Import getResource()
 * @method int getSessionId()
 * @method $this setSessionId(int $value)
 * @method int getSerialNumber()
 * @method $this setSerialNumber(int $value)
 * @method string getValue()
 * @method $this setValue(string $value)
 * @method int getStatus()
 * @method $this setStatus(int $value)
 */
class Mage_Dataflow_Model_Import extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('dataflow/import');
    }
}
