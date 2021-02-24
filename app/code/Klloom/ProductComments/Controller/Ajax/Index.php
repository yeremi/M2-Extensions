<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/27/18
 * Time: 9:40 PM
 */

namespace Klloom\ProductComments\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var
     */
    protected $request;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var FormKeyValidator
     */
    protected $_formKeyValidator;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var DateTime
     */
    protected $timezone;

    /**
     * Index constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Session $customerSession
     * @param FormKeyValidator $formKeyValidator
     * @param JsonFactory $resultJsonFactory
     * @param DateTime $timezone
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $customerSession,
        FormKeyValidator $formKeyValidator,
        JsonFactory $resultJsonFactory,
        DateTime $timezone
    )
    {
        $this->_registry          = $registry;
        $this->_customerSession   = $customerSession;
        $this->_formKeyValidator  = $formKeyValidator;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->timezone           = $timezone;
        parent::__construct($context);
    }

    public function __execute()
    {
    }

    public function execute()
    {

        $result    = $this->_resultJsonFactory->create();
        $dataArray = [];

        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }

        $customerId = $this->_getSession()->getCustomerId();
        if (!$customerId) {
            $this->messageManager->addErrorMessage('You must be logged in.');
            return;
        }

        //$customerName = $customerObj; //$this->_getSession()->getCustomer();
        $productId   = (int)$this->getRequest()->getParam('product_id');
        $productName = $this->getRequest()->getParam('product_name');
        $comment     = $this->getRequest()->getParam('comment');

        $date = $this->timezone->gmtDate();


        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            //$this->messageManager->addErrorMessage('Invalid Form Validation');
        } else {

            $dataArray = array(
                'customer_id'   => $customerId,
                'product_id'    => $productId,
                'comment'       => $comment,
                'created_at'    => $date
            );

            $question = $this->_objectManager->create('Klloom\ProductComments\Model\Post');
            foreach ($dataArray as $key => $value) {
                $question->setData($key, $value);
            }
            $question->save();
        }

        //return $result->setData($dataArray);

    }

    protected function _getSession()
    {
        return $this->_customerSession;
    }

}