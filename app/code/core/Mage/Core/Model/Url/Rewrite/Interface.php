<?php

/**
 * @category   Mage
 * @package    Mage_Core
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Url rewrite interface
 *
 * @category   Mage
 * @package    Mage_Core
 */
interface Mage_Core_Model_Url_Rewrite_Interface
{
    /**
     * Load rewrite information for request
     *
     * @param array|string $path
     * @return Mage_Core_Model_Url_Rewrite_Interface
     */
    public function loadByRequestPath($path);
}
