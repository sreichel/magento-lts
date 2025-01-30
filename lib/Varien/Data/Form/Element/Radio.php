<?php

/**
 * @category   Varien
 * @package    Varien_Data
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Form radio element
 *
 * @category   Varien
 * @package    Varien_Data
 */
class Varien_Data_Form_Element_Radio extends Varien_Data_Form_Element_Abstract
{
    /**
     * Varien_Data_Form_Element_Radio constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setType('radio');
        $this->setExtType('radio');
    }
}
