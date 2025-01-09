<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml grid item renderer number
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Number extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected $_defaultWidth = 100;

    /**
     * Returns value of the row
     *
     * @return mixed|string
     */
    protected function _getValue(Varien_Object $row)
    {
        $data = parent::_getValue($row);
        if (!is_null($data)) {
            $value = $data * 1;
            $sign = (bool) (int) $this->getColumn()->getShowNumberSign() && ($value > 0) ? '+' : '';
            if ($sign) {
                $value = $sign . $value;
            }
            return $value ? $value : '0'; // fixed for showing zero in grid
        }
        return $this->getColumn()->getDefault();
    }
}
