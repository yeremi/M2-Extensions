<?php

namespace Klloom\Productlike\Block\Product\ProductList\Item;


use Magento\Framework\View\Element\Template;

class Countlink extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory
     */
    protected $mpProductcollectionFactory;
    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory
     */
    protected $sellerCollectionFactory;
    /**
     * @var \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerCollectionFactory;
    /**
     * @var \Klloom\Productlike\Helper\Dataurl
     */
    protected $likeHelper;
    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $marketPlaceHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;


    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory $mpProductcollectionFactory,
        \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory,
        \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Klloom\Productlike\Helper\Dataurl $likeHelper,
        \Webkul\Marketplace\Helper\Data $marketPlaceHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->customerSession            = $customerSession;
        $this->mpProductcollectionFactory = $mpProductcollectionFactory;
        $this->sellerCollectionFactory    = $sellerCollectionFactory;
        $this->collectionFactory          = $collectionFactory;
        $this->customerCollectionFactory  = $customerCollectionFactory;
        $this->likeHelper                 = $likeHelper;
        $this->marketPlaceHelper          = $marketPlaceHelper;
        $this->storeManager               = $storeManager;
        $this->assetRepo                  = $assetRepo;
    }
    
    public function getTotalLikesByProductId(){
        if ($this->getParentBlock()->getProduct()) {
            $productLikeCollection = $this->collectionFactory->create();
            $productLikeCollection->addFieldToSelect('*');
            $productLikeCollection->addFieldToFilter('product_id', $this->getParentBlock()->getProduct()->getId());
            return $productLikeCollection;
        }
    }

    public function getCustomerLikesByProductId()
    {

        if ($this->getParentBlock()->getProduct()) {
            $productLikeCollection = $this->collectionFactory->create();
            $productLikeCollection->addFieldToSelect('*');
            $productLikeCollection->addFieldToFilter('product_id', $this->getParentBlock()->getProduct()->getId());
            $productLikeCollection->setCurPage(1)->setPageSize(3);
            $customerIds = [];
            foreach ($productLikeCollection as $customerlike) {

                $customerlike->getCustomerId();
                $customerIds[] = $customerlike->getCustomerId();
            }

            $customerCollection = $this->customerCollectionFactory->create();
            $customerCollection->addAttributeToSelect('entity_id');
            $customerCollection->addNameToSelect();
            $customerCollection->addAttributeToSelect('age');
            $customerCollection->addAttributeToFilter('entity_id', array('in' => $customerIds));
            return $customerCollection;
        }

    }

    public function getSellerProductCount($customerId)
    {
        $mpCollection = $this->mpProductcollectionFactory->create();
        $mpCollection->addFieldToSelect('seller_id');
        $mpCollection->addFieldToFilter('seller_id', $customerId);
        return $mpCollection->count();
    }

    /**
     * @param $customer_id
     * @return bool false | \Webkul\Marketplace\Model\ResourceModel\Seller\Collection
     */
    public function getSellerShopDetails($customer_id)
    {
        $sellerCollection = $this->sellerCollectionFactory->create();
        $sellerCollection->addFieldToSelect('*');
        $sellerCollection->addFieldToFilter('seller_id', $customer_id);
        $sellerCollection->setCurPage(1)->setPageSize(1);
        if ($sellerCollection->count() >= 0) {
            return $sellerCollection->getFirstItem();
        } else {
            return false;
        }

    }

    public function getLikeUrl()
    {
        if ($this->customerSession->isLoggedIn())
            return $this->likeHelper->getLikeUrl($this->getProductItemData()->getId());
        return false;
    }

    public function getProductItemData()
    {
        return $this->getParentBlock()->getProduct();
    }

    /** get Seller logo /customer logo
     * @param $comment
     * @return string
     */
    public function AvatarLogoPic($seller)
    {
        if ($seller && $seller->getLogoPic()
            && strlen($seller->getLogoPic() > 0)) {
            return $this->marketPlaceHelper->getMediaUrl() . 'avatar/' . $seller->getLogoPic();
        }
        return $this->getViewFileUrl('images/avatar/no-image.svg');
    }

    /**
     * @param $product_id
     * @return bool
     */
    public function isLiked($product_id)
    {
        $liked = false;
        $uid   = null;
        if ($this->isLoggin()) {
            $uid = $this->customerSession->getCustomerId();
        }
        if ($uid) {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('product_id', $product_id);
            $collection->addFieldToFilter('customer_id', $uid);
            $liked = $collection->count() ? true : false;
        }
        return $liked;
    }

    /**
     * @return bool
     */
    public function isLoggin()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @param $seller_id
     * @return string
     */
    public function avatar($seller_id)
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource      = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection    = $resource->getConnection();
        $table_seller  = $resource->getTableName('marketplace_userdata');

        /**
         * Read app/code/Klloom/ProductComments/Block/Comments.php to know why this sql query need to be different
         */
        $sql_for_seller = "SELECT logo_pic, store_id FROM " . $table_seller . " WHERE seller_id = " . $seller_id . " ";
        $result_seller  = $connection->fetchAll($sql_for_seller);
        $pseudo_store   = count($result_seller) > 1 ? 1 : 0;
        $avatar         = null;
        foreach ($result_seller as $seller) {
            if ($seller['store_id'] == $pseudo_store) {
                $avatar = $seller['logo_pic'];
            }
        }

        if ($avatar) {
            return $this->getMediaUrl() . 'avatar/thumb' . $avatar;
        } else {
            return $this->assetRepo->getUrl("images/avatar/no-image.svg");
        }
    }

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
    }
}