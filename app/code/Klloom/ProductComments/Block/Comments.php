<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/27/18
 * Time: 21:43 PM
 */

namespace Klloom\ProductComments\Block;

class Comments extends \Magento\Framework\View\Element\Template
{

    protected $_registry;
    /**
     * @var \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory
     */
    protected $commentCollectionFactory;
    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $marketPlaceHelper;
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    private $formKey;
    /**
     * @var \ Klloom\ProductComments\Helper\Data
     */
    private $helper;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;

    /**
     * Comments constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Framework\Registry $registry
     * @param \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory $commentCollectionFactory
     * @param \Webkul\Marketplace\Helper\Data $marketPlaceHelper
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Klloom\ProductComments\Helper\Data $helper
     * @param \Magento\Customer\Model\Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Registry $registry,
        \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory $commentCollectionFactory,
        \Webkul\Marketplace\Helper\Data $marketPlaceHelper,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Klloom\ProductComments\Helper\Data $helper,
        \Magento\Customer\Model\Session $session,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->marketPlaceHelper        = $marketPlaceHelper;
        $this->formKey                  = $formKey;
        $this->helper                   = $helper;
        $this->session                  = $session;
        $this->httpContext              = $httpContext;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentCustomer()
    {
        return $this->marketPlaceHelper->getCustomer();
    }

    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getAjaxUrl()
    {
        return $this->getUrl("klloom_productcomments/ajax/index", ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getAjaxUrlDelete()
    {
        return $this->getUrl("klloom_productcomments/ajax/delete", ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getAjaxUrlRead()
    {
        return $this->getUrl("klloom_productcomments/ajax/read", ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getAjaxUrlSave()
    {
        return $this->getUrl("klloom_productcomments/ajax/save", ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * get Current product Comment Collection
     * @return  Comment Collection
     */
    public function getCommentCollection()
    {
        $collection = $this->commentCollectionFactory->create();
        $collection->addSellerDataWithStoreFilter($this->getStoreId());
        $collection->addFieldToSelect('*')
            ->addFieldToFilter('product_id', (int)$this->getCurrentProduct()->getId());
        return $collection;
    }

    /** get Seller logo /customer logo
     * @param $comment
     * @return string
     */
    public function AvatarLogoPic($comment)
    {
        if ($comment->getSellerLogoPic()
            && strlen($comment->getSellerLogoPic() > 0)) {
            return $this->marketPlaceHelper->getMediaUrl() . 'avatar/' . $comment->getSellerLogoPic();
        }
        return $this->getViewFileUrl('images/avatar/no-image.svg');
    }

    public function getComments($productId)
    {

        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $resource       = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection     = $resource->getConnection();
        $table_comments = $resource->getTableName('klloom_comments');
        $table_seller   = $resource->getTableName('marketplace_userdata');
        $table_customer = $resource->getTableName('customer_entity');

        // TODO Fix this non-standard selecting the minimum necessary
        /*$sql = "SELECT
                
                c.comment_id, c.comment, c.created_at, c.customer_id,  
                s.shop_title, s.logo_pic, s.shop_url, 
                u.firstname, u.lastname 
                 
                FROM " . $table_comments . " as c
                INNER JOIN " . $table_seller . " AS s
                INNER JOIN " . $table_customer . " AS u
                ON s.seller_id = c.customer_id 
                AND c.product_id = " . $productId . " 
                AND s.store_id = 1 
                AND u.entity_id = s.seller_id  
                ORDER BY c.created_at DESC
                ";*/

        $sql = "SELECT 

                c.comment_id, c.comment, c.created_at, c.customer_id, 
                u.firstname, u.lastname 
                
                FROM " . $table_comments . " as c
                INNER JOIN " . $table_customer . " AS u
                ON c.product_id = " . $productId . " 
                AND u.entity_id = c.customer_id  
                ORDER BY c.created_at DESC";

        $collection = $connection->fetchAll($sql);

        $comments = [];
        foreach ($collection as $comment) {

            /**
             * This second query is necessary in order to know the correct store_id of seller after create a new account.
             * WK Marketplace Logic:
             * When user create a new account and start commenting without update nothing on profile the store_id saved is 0
             * When user create a new account and update data on on profile the store_id saved is 1
             */
            $sql_for_seller = "SELECT shop_title, logo_pic, shop_url, store_id, is_seller  FROM " . $table_seller . " WHERE seller_id = " . $comment['customer_id'] . " ";

            $result_seller = $connection->fetchAll($sql_for_seller);
            $pseudo_store  = count($result_seller) > 1 ? 1 : 0;
            foreach ($result_seller as $seller) {
                if ($seller['store_id'] == $pseudo_store) {
                    $comment['logo_pic']   = $seller['logo_pic'];
                    $comment['shop_title'] = $seller['shop_title'];
                    $comment['shop_url']   = $seller['shop_url'];
                }
            }

            $username = '@' . $comment['shop_url'];

            $remove = false;
            if ($this->isLoggedIn()) {
                $userId = $this->getCustomer()->getId();
                if ($comment['customer_id'] === $userId) {
                    $remove = $this->getAjaxUrlDelete() . '?pi=' . md5($productId) . '&ci=' . md5($comment['comment_id']);
                }
            }

            $comments[] = [
                'comment'   => nl2br($comment['comment']),
                'username'  => $username,
                'created'   => $this->helper->time_elapsed_string($comment['created_at']),
                'avatar'    => $this->helper->avatar($comment['logo_pic']),
                'profile'   => $comment['shop_url'],
                'canRemove' => $remove
            ];
        }

        return $comments;
    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }

    public function isLoggedIn()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getCurrentSellerAvatar()
    {
        $user   = $this->marketPlaceHelper->getSeller();
        $avatar = $user['logo_pic'] != 'noimage.png' ? $user['logo_pic'] : null;
        return $this->helper->avatar($avatar);
    }

    public function loginUrl()
    {
        $url = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        return $this->getUrl('customer/account/login', array('referer' => base64_encode($url)));
    }

}