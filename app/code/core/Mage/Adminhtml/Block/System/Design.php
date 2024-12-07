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
 * @copyright  Copyright (c) 2022-2023 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_System_Design extends Mage_Adminhtml_Block_Template
{
    public const BLOCK_GRID = 'grid';

    public const BUTTON_ADD = 'add_new_button';

    protected $_template = 'system/design/index.phtml';

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        $this->setChild(self::BLOCK_GRID, $this->getLayout()->createBlock('adminhtml/system_design_grid', 'design.grid'));

        $this->addButtons();
        return parent::_prepareLayout();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function addButtons(): void
    {
        $this->setChild(self::BUTTON_ADD, $this->getButtonAddBlock());
    }

    public function getButtonAddBlock(): Mage_Adminhtml_Block_Widget_Button
    {
        return parent::getButtonBlockByType(self::BUTTON_ADD)
            ->setLabel(Mage::helper('catalog')->__('Add Design Change'))
            ->setOnClickSetLocationJsUrl('*/*/new');
    }
}
