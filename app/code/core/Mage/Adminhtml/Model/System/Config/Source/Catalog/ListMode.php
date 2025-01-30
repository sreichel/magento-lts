<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Source_Catalog_ListMode
{
    public function toOptionArray()
    {
        return [
            //array('value'=>'', 'label'=>''),
            ['value' => 'grid', 'label' => Mage::helper('adminhtml')->__('Grid Only')],
            ['value' => 'list', 'label' => Mage::helper('adminhtml')->__('List Only')],
            ['value' => 'grid-list', 'label' => Mage::helper('adminhtml')->__('Grid (default) / List')],
            ['value' => 'list-grid', 'label' => Mage::helper('adminhtml')->__('List (default) / Grid')],
        ];
    }
}
