<?php

/**
 * @category   Mage
 * @package    Mage_Install
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Install
 */
class Mage_Install_Controller_Action extends Mage_Core_Controller_Varien_Action
{
    protected function _construct()
    {
        parent::_construct();

        Mage::getDesign()->setArea('install')
            ->setPackageName('default')
            ->setTheme('default');

        $this->getLayout()->setArea('install');

        $this->setFlag('', self::FLAG_NO_CHECK_INSTALLATION, true);
    }
}
