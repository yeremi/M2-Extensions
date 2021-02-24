<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 20/09/18
 * Time: 08:52
 */

namespace Klloom\Related\Block\Product\View\Related;


/**
 * Class PhotoCategory
 * @package Klloom\Related\Block\Product\View\Related
 */
class PhotoCategory extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * PhotoCategory constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productRepository         = $productRepository;
        $this->_registry                  = $registry;
        $this->_productCollectionFactory  = $productCollectionFactory;
    }

    /**
     * @param $id
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductsCollectionByCategoryId()
    {
        $current_product_id = $this->getCurrentProduct()->getId();

        $categories = $this->getCategoriesByCurrentProduct();
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $categories]);
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addFieldToFilter('entity_id', ['nin' => $current_product_id]);
        $collection->setPageSize(16);
        $collection->getSelect()->orderRand();
        return $collection;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
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

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * Get category collection
     *
     * @param bool $isActive
     * @param bool|int $level
     * @param bool|string $sortBy
     * @param bool|int $pageSize
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection or array
     */
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
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductCommentLikeHtml(
        \Magento\Catalog\Model\Product $product
    )
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
        return $commentandlikeRenderBlock->toHtml();

    }

}