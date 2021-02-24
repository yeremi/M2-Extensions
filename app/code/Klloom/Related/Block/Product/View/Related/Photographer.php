<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 19/09/18
 * Time: 20:15
 */

namespace Klloom\Related\Block\Product\View\Related;


/**
 * Class Photographer
 * @package Klloom\Related\Block\Product\View\Related
 */
class Photographer extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * Photographer constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->registry                 = $registry;
        $this->resourceConnection       = $resourceConnection;
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
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
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
            $productCollection = $this->productCollectionFactory->create();
            $productCollection->addAttributeToSelect('*');
            $productCollection->addFieldToFilter('entity_id', ['in' => $productIds]);
            $productCollection->addFieldToFilter('entity_id', ['nin' => $current_product_id]);
            $productCollection->setPageSize(4);
            $productCollection->getSelect()->orderRand();
        }
        return $productCollection;
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

        return $commentandlikeRenderBlock->toHtml();
    }

}