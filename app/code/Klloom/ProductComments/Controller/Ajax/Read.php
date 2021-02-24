<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 14/09/18
 * Time: 11:50
 */

namespace Klloom\ProductComments\Controller\Ajax;

use Magento\Framework\App\ResponseInterface;
use DateTime;

class Read extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    private $resultRawFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Klloom\ProductComments\Block\Comments
     */
    private $comments;

    /**
     * Read constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $timezone
     * @param \Klloom\ProductComments\Block\Comments $comments
     */
    function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Customer\Model\Session $session,
        \Klloom\ProductComments\Block\Comments $comments
    )
    {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory  = $resultRawFactory;
        $this->session           = $session;
        $this->comments          = $comments;
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if (!$params) {
            $this->_redirect('*/*/');
            return;
        }

        if (!isset($params['product_id'])) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('*/*/');
            return;
        }

        /**
         * Retrieve comments custom collection
         */
        $productId  = $params['product_id'];
        $comments   = $this->comments->getComments($productId);
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($comments);
    }

}