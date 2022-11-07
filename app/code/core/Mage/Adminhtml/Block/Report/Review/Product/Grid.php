<?php
/**
 * OpenMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml reviews by products report grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Report_Review_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('gridProducts');
        $this->setDefaultSort('review_cnt');
        $this->setDefaultDir('desc');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('reports/review_product_collection')
            ->joinReview();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', [
            'header'    =>Mage::helper('reports')->__('ID'),
            'width'     =>'50px',
            'index'     =>'entity_id'
        ]);

        $this->addColumn('name', [
            'header'    => Mage::helper('reports')->__('Product Name'),
            'index'     => 'name'
        ]);

        $this->addColumn('review_cnt', [
            'header'    =>Mage::helper('reports')->__('Number of Reviews'),
            'width'     =>'50px',
            'align'     =>'right',
            'index'     =>'review_cnt'
        ]);

        $this->addColumn('avg_rating', [
            'header'    =>Mage::helper('reports')->__('Avg. Rating'),
            'width'     =>'50px',
            'align'     =>'right',
            'index'     =>'avg_rating'
        ]);

        $this->addColumn('avg_rating_approved', [
            'header'    =>Mage::helper('reports')->__('Avg. Approved Rating'),
            'width'     =>'50px',
            'align'     =>'right',
            'index'     =>'avg_rating_approved'
        ]);

        $this->addColumn('last_created', [
            'header'    =>Mage::helper('reports')->__('Last Review'),
            'width'     =>'150px',
            'index'     =>'last_created',
            'type'      =>'datetime'
        ]);

        $this->addColumn('action', [
            'header'    => Mage::helper('reports')->__('Action'),
            'width'     => '100px',
            'align'     => 'center',
            'filter'    => false,
            'sortable'  => false,
            'renderer'  => 'adminhtml/report_grid_column_renderer_product',
            'is_system' => true
        ]);

        $this->setFilterVisibility(false);

        $this->addExportType('*/*/exportProductCsv', Mage::helper('reports')->__('CSV'));
        $this->addExportType('*/*/exportProductExcel', Mage::helper('reports')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/catalog_product_review/', ['productId' => $row->getId()]);
    }
}
