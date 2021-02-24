<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 24/09/18
 * Time: 00:57
 */

namespace Klloom\Related\Block\Product\View\Related;


class Related extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $_categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;

    function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->registry                  = $registry;
        $this->resourceConnection        = $resourceConnection;
        $this->_productCollectionFactory  = $productCollectionFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->productRepository         = $productRepository;
    }

    /**
     * @return \Magento\Framework\View\Element\Template
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return mixed
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * @param $customerId
     * @return mixed
     */
    public function getProductsByCustomer($customerId)
    {
        $current_product_id = $this->getCurrentProduct()->getId();
        /**
         * SELECT mageproduct_id FROM marketplace_product
         * WHERE status = 1
         * AND is_approved = 1
         * AND seller_id = ?;
         */
        $resource   = $this->resourceConnection;
        $connection = $resource->getConnection();
        $tableName  = $this->getTableName();
        $sql        = "SELECT mageproduct_id FROM " . $tableName . " WHERE status = 1 AND is_approved = 1 AND seller_id = " . $customerId;
        $result     = $connection->fetchAll($sql);

        $productCollection = false;
        if (count($result) > 3) {
            $productIds        = array_column($connection->fetchAll($sql), 'mageproduct_id');
            $productCollection = $this->_productCollectionFactory->create();
            $productCollection->addAttributeToSelect('*');
            $productCollection->addFieldToFilter('entity_id', ['in' => $productIds]);
            $productCollection->addFieldToFilter('entity_id', ['nin' => $current_product_id]);
            $productCollection->setPageSize(4);
            $productCollection->getSelect()->orderRand();
        }
        return $productCollection;
    }

    public function getProductsCollectionByCategoryId($nin)
    {
        $current_product_id = $this->getCurrentProduct()->getId();

        $categories = $this->getCategoriesByCurrentProduct();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $categories]);
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        //$collection->addFieldToFilter('entity_id', ['nin' => $current_product_id]);
        $collection->addFieldToFilter('entity_id', ['nin' => $nin]);
        $collection->setPageSize(16);
        $collection->getSelect()->orderRand();
        return $collection;
    }

    public function getCategoriesByCurrentProduct()
    {
        $product     = $this->getCurrentProduct();
        $categoryIds = $product->getCategoryIds();
        $categories  = $this->getCategoryCollection()->addAttributeToFilter('entity_id', $categoryIds);

        $cats = [];
        foreach ($categories as $category) {
            $cats[] = $category->getID();
        }
        return $cats;
    }

    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->resourceConnection->getTableName('marketplace_product');
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCommentLikeHtml(
        \Magento\Catalog\Model\Product $product
    )
    {
        $commentandlikeRenderBlock = $this->getLayout()
            ->createBlock(\Klloom\ProductComments\Block\Product\ProductList\Item\CommentLikeContainer\Renderer::class, '', ['product' => $product])
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

}