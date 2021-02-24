<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/25/18
 * Time: 11:46 AM
 */

namespace Klloom\AbuseReport\Block;

class Product extends \Magento\Framework\View\Element\Template
{

    protected $_registry;
    /**
     * @var \Klloom\AbuseReport\Model\ResourceModel\Post\Post
     */
    private $abuseCollection;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Product constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Klloom\AbuseReport\Model\ResourceModel\Post\CollectionFactory $abuseCollection
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Klloom\AbuseReport\Model\ResourceModel\Post\CollectionFactory $abuseCollection,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
        $this->abuseCollection = $abuseCollection;
        $this->_objectManager = $objectManager;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getAjaxUrl()
    {
        return $this->getUrl("klloom_abusereport/ajax/index");
    }

    public function isStopped($productId){

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->_objectManager->create(\Magento\Catalog\Model\Product::class);
        $status = $product->load($productId)->getStatus();

        $productCollection = $this->abuseCollection->create();
        //$productCollection->addAttributeToSelect('report');
        $productCollection->addFieldToFilter('product_id', ['eq' => $productId]);
        $productCollection->setPageSize(1);
        $productCollection->getSelect();
        if($status == 2 and $productCollection){
            return $productCollection;
        } else {
            return false;
        }
    }

}