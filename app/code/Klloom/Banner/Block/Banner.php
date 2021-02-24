<?php
/**
 * https://blog.qaisarsatti.com/magento_2/magento-2-get-product-images/
 */

namespace Klloom\Banner\Block;

class Banner extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $_imageBuilder;
    /**
     * @var \Klloom\Trending\Block\Data
     */
    private $trending;

    /**
     * Banner constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Block\Product\ImageBuilder $_imageBuilder
     * @param \Klloom\Trending\Block\Data $trending
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Block\Product\ImageBuilder $_imageBuilder,
        \Klloom\Trending\Block\Data $trending,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_imageBuilder             = $_imageBuilder;
        parent::__construct($context, $data);
        $this->trending = $trending;
    }

    public function getProductCollection()
    {
        $productIds = $this->trending->getProductsIdsByRanking();
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $productCollection->addFieldToFilter('entity_id', ['in' => $productIds]);
        $productCollection->setPageSize(1);
        $productCollection->getSelect()->orderRand();
        return $productCollection;
    }

    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->_imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    public function getMediaBaseUrl()
    {
        $om           = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getPercentageDonation()
    {
        return $this->_scopeConfig->getValue(
            'donation/general/percentage',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPercentagePhotographer()
    {
        return $this->_scopeConfig->getValue(
            'marketplace/general_settings/percent',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}