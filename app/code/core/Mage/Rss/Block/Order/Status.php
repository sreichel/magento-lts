<?php

/**
 * @category   Mage
 * @package    Mage_Rss
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Review form block
 *
 * @category   Mage
 * @package    Mage_Rss
 */
class Mage_Rss_Block_Order_Status extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        /*
        * setting cache to save the rss for 10 minutes
        */
        $this->setCacheKey('rss_order_status_' . $this->getRequest()->getParam('data'));
        $this->setCacheLifetime(600);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $rssObj = Mage::getModel('rss/rss');
        $order = Mage::registry('current_order');
        $title = Mage::helper('rss')->__('Order # %s Notification(s)', $order->getIncrementId());
        $newurl = Mage::getUrl('sales/order/view', ['order_id' => $order->getId()]);
        $data = ['title' => $title,
            'description' => $title,
            'link'        => $newurl,
            'charset'     => 'UTF-8',
        ];
        $rssObj->_addHeader($data);
        $resourceModel = Mage::getResourceModel('rss/order');
        $results = $resourceModel->getAllCommentCollection($order->getId());
        if ($results) {
            foreach ($results as $result) {
                $urlAppend = 'view';
                $type = $result['entity_type_code'];
                if ($type && $type != 'order') {
                    $urlAppend = $type;
                }
                $type  = Mage::helper('rss')->__(ucwords($type));
                $title = Mage::helper('rss')->__('Details for %s #%s', $type, $result['increment_id']);

                $description = '<p>' .
                Mage::helper('rss')->__('Notified Date: %s<br/>', $this->formatDate($result['created_at'])) .
                Mage::helper('rss')->__('Comment: %s<br/>', $result['comment']) .
                '</p>'
                ;
                $url = Mage::getUrl('sales/order/' . $urlAppend, ['order_id' => $order->getId()]);
                $data = [
                    'title'         => $title,
                    'link'          => $url,
                    'description'   => $description,
                ];
                $rssObj->_addEntry($data);
            }
        }
        $title = Mage::helper('rss')->__('Order #%s created at %s', $order->getIncrementId(), $this->formatDate($order->getCreatedAt()));
        $url = Mage::getUrl('sales/order/view', ['order_id' => $order->getId()]);
        $description = '<p>' .
            Mage::helper('rss')->__('Current Status: %s<br/>', $order->getStatusLabel()) .
            Mage::helper('rss')->__('Total: %s<br/>', $order->formatPrice($order->getGrandTotal())) .
            '</p>'
        ;
        $data = [
            'title'         => $title,
            'link'          => $url,
            'description'   => $description,
        ];
        $rssObj->_addEntry($data);
        return $rssObj->createRssXml();
    }
}
