<?php

/**
 * @category   Varien
 * @package    Varien_Filter
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Varien_Filter_Object_Grid extends Varien_Filter_Object
{
    /**
     * @param Varien_Object|array $grid
     * @return array
     * @throws Exception
     */
    public function filter($grid)
    {
        $out = [];
        if (is_array($grid)) {
            foreach ($grid as $i => $array) {
                $out[$i] = parent::filter($array);
            }
        }
        return $out;
    }
}
