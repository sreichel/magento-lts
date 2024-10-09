<?php
/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Api
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * users block
 *
 * @category   Mage
 * @package    Mage_Api
 */
class Mage_Api_Block_Adminhtml_Users extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('api/users.phtml');
    }

    /**
     * @return string
     */
    public function getAddNewUrl()
    {
        return $this->getUrl('*/*/edituser');
    }

    /**
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getLayout()->createBlock('api/adminhtml_grid_user')->toHtml();
    }
}
