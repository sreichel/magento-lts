<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product tax admin controller
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_TaxController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Set tax ignore notification flag and redirect back
     */
    public function ignoreTaxNotificationAction()
    {
        $section = $this->getRequest()->getParam('section');
        if ($section) {
            Mage::helper('tax')->setIsIgnored('tax/ignore_notification/' . $section, true);
        }
        $this->_redirectReferer();
    }

    /**
     * Check is allowed access to action
     *
     * @return true
     */
    protected function _isAllowed()
    {
        return true;
    }
}
