<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 01/10/18
 * Time: 14:28
 */

namespace Klloom\ProductCategories\Block\Category;


class Toolbar extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    private $categoryHelper;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Toolbar constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryHelper            = $categoryHelper;
        $this->registry                  = $registry;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function isActive($sku)
    {
        if ($this->getCurrentCategory() && $this->getCurrentCategory()->getUrlKey() == $sku) {
            return 'is-active ' . $this->getCurrentCategory()->getUrlKey();
        }
    }

    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->categoryCollectionFactory->create();
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

        // set pagination
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

        return $collection;
    }

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }
}