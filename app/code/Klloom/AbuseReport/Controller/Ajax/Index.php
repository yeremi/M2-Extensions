<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/25/18
 * Time: 3:59 PM
 */

namespace Klloom\AbuseReport\Controller\Ajax;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $request;
    protected $resultJsonFactory;
    protected $_customerSession;
    protected $_formKeyValidator;
    protected $timezone;
    /**
     * @var \Klloom\AbuseReport\Model\MailInterface
     */
    protected $mail;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $imageHelperFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Klloom\AbuseReport\Model\MailInterface $mail
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $timezone
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Klloom\AbuseReport\Model\MailInterface  $mail,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $timezone,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_customerSession  = $customerSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->timezone          = $timezone;
        $this->mail = $mail;
        $this->productRepository = $productRepository;
        $this->imageHelperFactory = $imageHelperFactory;
        parent::__construct($context);

        $this->storeManager = $storeManager;
    }



    public function execute()
    {

        $result    = $this->resultJsonFactory->create();

        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }

        $customerId = $this->_getSession()->getCustomerId();
        if (!$customerId) {
            $this->messageManager->addErrorMessage('You must be logged in to report.');
            return;
        }

        $productId = (int)$this->getRequest()->getParam('product_id');
        $report    = $this->getRequest()->getParam('report');
        $date      = $this->timezone->gmtDate();

        $dataArray = array(
            'customer_id' => $customerId,
            'product_id'  => $productId,
            'report'      => $report,
            'created_at'  => $date
        );

        $question = $this->_objectManager->create('Klloom\AbuseReport\Model\Post');
        foreach ($dataArray as $key => $value) {
            $question->setData($key, $value);
        }
        $question->save();

        $data = array(
            'customer_id'   => $customerId,
            'product_id'    => $productId,
            'action_report' => $question->getId(),
            'method'        => 'insert'
        );
        $this->_eventManager->dispatch('klloom_trending_events', array('klloom_event' => $data));

        $currentStore = $this->storeManager->getStore();
        $_product = $this->getProduct($productId);

        if(!$_product)
        {
            $this->messageManager->addErrorMessage('Product is not exits.');
            return;
        }
        $customerName = $this->_customerSession->getCustomer()->getName();
        $customerEmail = $this->_customerSession->getCustomer()->getEmail();

        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $maildata= [
            'report_type'=>$report,
            'product_name' => $_product->getName(),
            'product_url' => $_product->getProductUrl(),
            'product_photo'   => $mediaUrl.'catalog/product'.$_product->getData('image'),
            'reply_to_name' => $customerName
        ];

        $this->mail->send($customerEmail,$maildata);

    }

    protected function _getSession()
    {
        return $this->_customerSession;
    }

    /**
     * @param $productId
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface|mixed
     */
    protected  function getProduct($productId)
    {
        try{
            $_product =  $this->productRepository->getById($productId);
            return $_product;
        }catch (\Magento\Framework\Exception\NoSuchEntityException  $exception){
           return false;
        }
        return false;

    }
}