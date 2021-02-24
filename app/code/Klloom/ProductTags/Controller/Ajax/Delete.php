<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 2019-01-31
 * Time: 20:26
 */

namespace Klloom\ProductTags\Controller\Ajax;

class Delete extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $_helper;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_resultJsonFactory;
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    private $_resultRawFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $_session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Customer\Model\Session $session
    )
    {
        parent::__construct($context);
        $this->_helper            = $helper;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_resultRawFactory  = $resultRawFactory;
        $this->_session           = $session;
    }

    public function execute()
    {

        $params = $this->getRequest()->getParam('id');
        $model  = $this->_objectManager->create('Klloom\ProductTags\Model\Post');

        if (!$params || !$this->getRequest()->isAjax() || !$this->getCustomer()->getId()) {
            $this->_redirect('*/*/');
            return;
        }

        $field['product_id'] = $params;
        $field['profile_id'] = $this->getCustomer()->getId();
        $model->setData($field)->save();

    }

    public function getCustomer()
    {
        return $this->_session->getCustomer();
    }

}