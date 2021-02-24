<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 13/09/18
 * Time: 08:21
 */

namespace Klloom\ProductComments\Controller\Ajax;

/*use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;*/

use DateTime;

class Save extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Klloom\ProductComments\Block
     */
    private $comments;

    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Customer\Model\Session $session
     * @param \Klloom\ProductComments\Block\Comments $comments
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Customer\Model\Session $session,
        \Klloom\ProductComments\Block\Comments $comments
    )
    {
        parent::__construct($context);
        $this->helper            = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory  = $resultRawFactory;
        $this->session           = $session;
        $this->comments          = $comments;
    }

    public function execute()
    {

        $model  = $this->_objectManager->create('Klloom\ProductComments\Model\Post');
        $params = $this->helper->jsonDecode($this->getRequest()->getContent());
        if (!$params) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getCustomer()->getId()) {
            $this->_redirect('*/*/');
            return;
        }

        $params['customer_id'] = $this->getCustomer()->getId();

        // TODO Validate form_key before save
        /**
         * Store
         */
        $model->setData($params)->save();

        // <--- START Klloom Event Manager for Trending --->
        $data = array(
            'customer_id'    => $params['customer_id'],
            'product_id'     => $params['product_id'],
            'action_comment' => $model->getId(),
            'method'         => 'insert'
        );
        $this->_eventManager->dispatch('klloom_trending_events', array('klloom_event' => $data));
        // <--- END --->

        /**
         * Retrieve comments custom collection
         */
        $productId  = $params['product_id'];
        $comments   = $this->comments->getComments($productId);
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($comments);
    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }

}