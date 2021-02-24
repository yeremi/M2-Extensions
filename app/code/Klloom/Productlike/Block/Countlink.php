<?php
/**
 * User: Amit Bera
 */

namespace Klloom\Productlike\Block;

use Magento\Framework\View\Element\Template;

class Countlink extends Template
{
    /**
     * @var \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory
     */
    protected $likeproductItem;
    protected $collectionFactory;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerFactory;
    /**
     * @var \Magento\Reports\Model\ResourceModel\Product\Collection
     */
    protected $prodCollection;
    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory
     */
    protected $likecollectionFactory;

    /**
     * @var \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory
     */
    private $sellerCollectionFactory;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * Countlink constructor.
     * @param Template\Context $context
     * @param \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory
     * @param \Magento\Reports\Model\ResourceModel\Product\Collection $prodCollection
     * @param \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory $mpProductcollectionFactory
     * @param \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Customer\Model\Session $session
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $collectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Reports\Model\ResourceModel\Product\Collection $prodCollection,
        \Webkul\Marketplace\Model\ResourceModel\Product\CollectionFactory $mpProductcollectionFactory,
        \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Session $session,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory          = $collectionFactory;
        $this->customerFactory            = $customerFactory;
        $this->prodCollection             = $prodCollection;
        $this->mpProductcollectionFactory = $mpProductcollectionFactory;
        $this->sellerCollectionFactory    = $sellerCollectionFactory;
        $this->httpContext                = $httpContext;
        $this->session                    = $session;
        $this->productCollectionFactory   = $productCollectionFactory;
    }

    public function getCustomerlikeFromProductId()
    {

        $productlikeCollection = $this->collectionFactory->create();
        $productlikeCollection->addFieldToSelect('*');
        $productlikeCollection->addFieldToFilter('product_id', $this->getProductItemData()->getId());
        $productlikeCollection->setCurPage(1)->setPageSize(3);
        $storecustomerid = array();
        foreach ($productlikeCollection as $customerlike) {

            $customerlike->getCustomerId();
            $storecustomerid[] = $customerlike->getCustomerId();
        }

        $customercollection = $this->customerFactory->create();
        $customercollection->addAttributeToSelect('entity_id');
        $customercollection->addNameToSelect();
        $customercollection->addAttributeToSelect('age');
        $customercollection->addAttributeToFilter('entity_id', array('in' => $storecustomerid));
        return $customercollection;
    }

    function getMyLikes()
    {
        $customerId            = $this->getCustumerId();
        $productlikeCollection = $this->collectionFactory->create();
        $productlikeCollection->addFieldToSelect('product_id');
        $items = $productlikeCollection->addFieldToFilter('customer_id', $customerId)->toArray();
        $Ids   = array_column($items['items'], 'product_id');

        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('entity_id', ['in' => $Ids]);
        return $collection;

    }

    public function getProductCommentLikeHtml(\Magento\Catalog\Model\Product $product)
    {

        $commentandlikeRenderBlock = $this->getLayout()
            ->createBlock(\Klloom\ProductComments\Block\Product\ProductList\Item\CommentLikeContainer\Renderer::class,
                '',
                ['product' => $product]
            )
            ->setTemplate('Klloom_ProductComments::product/list/comment-like-container.phtml')
            ->setProduct($product);

        $likeBlock = $this->getLayout()
            ->createBlock(\Klloom\Productlike\Block\Product\ProductList\Item\Countlink::class)
            ->setTemplate('Klloom_Productlike::product/list/like.phtml');
        $commentandlikeRenderBlock->setChild('likescount', $likeBlock);

        $purchaseBlock = $this->getLayout()
            ->createBlock(\Klloom\Photo\Block\Product\ProductList\Item\Block::class)
            ->setTemplate('Klloom_Photo::product/list/purchase.phtml')
            ->setProduct($product);

        $commentandlikeRenderBlock->setChild('purchase', $purchaseBlock);

        return $commentandlikeRenderBlock->toHtml();

    }

    public function getProductItemData()
    {
        $this->likeproductItem = $this->getData('product');
        return $this->likeproductItem;
    }


    public function getSellerProductCount($customer_id)
    {
        $mpCollection = $this->mpProductcollectionFactory->create();
        $mpCollection->addFieldToSelect('seller_id');
        $mpCollection->addFieldToFilter('seller_id', $customer_id);
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

    public function getCustumerId()
    {
        return $this->getCustomer() ? $this->getCustomer()->getId() : false;
    }

    public function isLoggedIn()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }

    public function loginUrl()
    {
        $url = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        return $this->getUrl('customer/account/login', array('referer' => base64_encode($url)));
    }

    public function createAccountUrl()
    {
        return $this->getUrl('customer/account/create');
    }

}