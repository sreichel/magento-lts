<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Flat sales order abstract comments collection, used as parent for: invoice, shipment, creditmemo
 *
 * @category   Mage
 * @package    Mage_Sales
 */
abstract class Mage_Sales_Model_Resource_Order_Comment_Collection_Abstract extends Mage_Sales_Model_Resource_Collection_Abstract
{
    /**
     * Set filter on comments by their parent item
     *
     * @param Mage_Core_Model_Abstract|int $parent
     * @return $this
     */
    public function setParentFilter($parent)
    {
        if ($parent instanceof Mage_Core_Model_Abstract) {
            $parent = $parent->getId();
        }
        return $this->addFieldToFilter('parent_id', $parent);
    }

    /**
     * Adds filter to get only 'visible on front' comments
     *
     * @param int $flag
     * @return $this
     */
    public function addVisibleOnFrontFilter($flag = 1)
    {
        return $this->addFieldToFilter('is_visible_on_front', $flag);
    }

    /**
     * Set created_at sort order
     *
     * @param string $direction
     * @return $this
     */
    public function setCreatedAtOrder($direction = 'desc')
    {
        return $this->setOrder('created_at', $direction);
    }
}
