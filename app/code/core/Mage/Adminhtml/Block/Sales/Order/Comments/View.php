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
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Invoice view  comments form
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Sales_Order_Comments_View extends Mage_Adminhtml_Block_Template
{
    /**
     * Retrieve required options from parent
     */
    protected function _beforeToHtml()
    {
        if (!$this->getParentBlock()) {
            Mage::throwException(Mage::helper('adminhtml')->__('Invalid parent block for this block.'));
        }
        $this->setEntity($this->getParentBlock()->getSource());
        return parent::_beforeToHtml();
    }

    /**
     * @codeCoverageIgnore
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->addButtons();
        return parent::_prepareLayout();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function addButtons(): void
    {
        $this->setChild(self::BUTTON_SUBMIT, $this->getButtonSaveBlock());
    }

    public function getButtonSaveBlock(): Mage_Adminhtml_Block_Widget_Button
    {
        return parent::getButtonBlockByType(self::BUTTON_SAVE)
            ->setId('submit_comment_button')
            ->setLabel(Mage::helper('sales')->__('Submit Comment'));
    }

    public function getSubmitUrl()
    {
        return $this->getUrl('*/*/addComment', ['id' => $this->getEntity()->getId()]);
    }

    public function canSendCommentEmail()
    {
        switch ($this->getParentType()) {
            case 'invoice':
                return Mage::helper('sales')->canSendInvoiceCommentEmail(
                    $this->getEntity()->getOrder()->getStore()->getId()
                );
            case 'shipment':
                return Mage::helper('sales')->canSendShipmentCommentEmail(
                    $this->getEntity()->getOrder()->getStore()->getId()
                );
            case 'creditmemo':
                return Mage::helper('sales')->canSendCreditmemoCommentEmail(
                    $this->getEntity()->getOrder()->getStore()->getId()
                );
        }

        return true;
    }

    /**
     * Replace links in string
     *
     * @param string|string[] $data
     * @param array|null $allowedTags
     * @return null|string|string[]
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return Mage::helper('adminhtml/sales')->escapeHtmlWithLinks($data, $allowedTags);
    }
}
