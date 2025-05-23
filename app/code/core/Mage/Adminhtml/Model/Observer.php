<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Installation event observer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_Observer
{
    public function bindLocale($observer)
    {
        if ($locale = $observer->getEvent()->getLocale()) {
            if ($choosedLocale = Mage::getSingleton('adminhtml/session')->getLocale()) {
                $locale->setLocaleCode($choosedLocale);
            }
        }
        return $this;
    }

    public function bindStore()
    {
        Mage::app()->setCurrentStore('admin');
        return $this;
    }

    /**
     * Prepare massaction separated data
     *
     * @return $this
     */
    public function massactionPrepareKey()
    {
        $request = Mage::app()->getFrontController()->getRequest();
        if ($key = $request->getPost('massaction_prepare_key')) {
            $value = is_array($request->getPost($key)) ? $request->getPost($key) : explode(',', $request->getPost($key));
            $request->setPost($key, $value ? $value : null);
        }
        return $this;
    }

    /**
     * Clear result of configuration files access level verification in system cache
     *
     * @return $this
     */
    public function clearCacheConfigurationFilesAccessLevelVerification()
    {
        Mage::app()->removeCache(Mage_Adminhtml_Block_Notification_Security::VERIFICATION_RESULT_CACHE_KEY);
        return $this;
    }
}
