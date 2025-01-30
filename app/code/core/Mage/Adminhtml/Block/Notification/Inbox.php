<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Adminhtml AdminNotification inbox grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Notification_Inbox extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'notification';
        $this->_headerText = Mage::helper('adminnotification')->__('Messages Inbox');
        parent::__construct();
    }

    protected function _prepareLayout()
    {
        $this->_removeButton('add');

        return parent::_prepareLayout();
    }
}
