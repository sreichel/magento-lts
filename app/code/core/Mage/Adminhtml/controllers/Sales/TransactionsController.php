<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Adminhtml
 */

/**
 * Adminhtml sales transactions controller
 *
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Sales_TransactionsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize payment transaction model
     *
     * @return Mage_Sales_Model_Order_Payment_Transaction | bool
     */
    protected function _initTransaction()
    {
        $txn = Mage::getModel('sales/order_payment_transaction')->load(
            $this->getRequest()->getParam('txn_id'),
        );

        if (!$txn->getId()) {
            $this->_getSession()->addError($this->__('Wrong transaction ID specified.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        $orderId = $this->getRequest()->getParam('order_id');
        if ($orderId) {
            $txn->setOrderUrl(
                $this->getUrl('*/sales_order/view', ['order_id' => $orderId]),
            );
        }

        Mage::register('current_transaction', $txn);
        return $txn;
    }

    public function indexAction()
    {
        $this->_title($this->__('Sales'))
            ->_title($this->__('Transactions'));

        $this->loadLayout()
            ->_setActiveMenu('sales/transactions')
            ->renderLayout();
    }

    /**
     * Ajax grid action
     */
    public function gridAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
    }

    /**
     * View Transaction Details action
     */
    public function viewAction()
    {
        $txn = $this->_initTransaction();
        if (!$txn) {
            return;
        }
        $this->_title($this->__('Sales'))
            ->_title($this->__('Transactions'))
            ->_title(sprintf('#%s', $txn->getTxnId()));

        $this->loadLayout()
            ->_setActiveMenu('sales/transactions')
            ->renderLayout();
    }

    /**
     * Fetch transaction details action
     */
    public function fetchAction()
    {
        $txn = $this->_initTransaction();
        if (!$txn) {
            return;
        }
        try {
            $txn->getOrderPaymentObject()
                ->setOrder($txn->getOrder())
                ->importTransactionInfo($txn);
            $txn->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__('The transaction details have been updated.'),
            );
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('adminhtml')->__('Unable to update transaction details.'),
            );
            Mage::logException($e);
        }
        $this->_redirect('*/sales_transactions/view', ['_current' => true]);
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        $aclPath = match ($action) {
            'fetch' => 'sales/transactions/fetch',
            default => 'sales/transactions',
        };

        return Mage::getSingleton('admin/session')->isAllowed($aclPath);
    }
}
