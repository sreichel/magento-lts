<?php

/**
 * @category   Mage
 * @package    Mage_ImportExport
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Import proxy product resource
 *
 * @category   Mage
 * @package    Mage_ImportExport
 */
class Mage_ImportExport_Model_Import_Proxy_Product_Resource extends Mage_Catalog_Model_Resource_Product
{
    /**
     * Product to category table.
     *
     * @return string
     */
    public function getProductCategoryTable()
    {
        return $this->_productCategoryTable;
    }

    /**
     * Product to website table.
     *
     * @return string
     */
    public function getProductWebsiteTable()
    {
        return $this->_productWebsiteTable;
    }
}
