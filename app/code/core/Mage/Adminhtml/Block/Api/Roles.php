<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * user roles block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Api_Roles extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('api/roles.phtml');
    }

    public function getAddNewUrl()
    {
        return $this->getUrl('*/*/editrole');
    }

    public function getGridHtml()
    {
        return $this->getLayout()->createBlock('adminhtml/api_grid_role')->toHtml();
    }
}
