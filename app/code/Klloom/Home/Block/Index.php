<?php
/**
 * Created by PhpStorm.
 * User: yeremi
 * Date: 2019-03-30
 * Time: 11:49
 */

namespace Klloom\Home\Block;

use Klloom\Trending\Block\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Index extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var ImageBuilder
     */
    protected $_imageBuilder;
    /**
     * @var Data
     */
    private $trending;

    /**
     * Banner constructor.
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param ImageBuilder $_imageBuilder
     * @param Data $trending
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        ImageBuilder $_imageBuilder,
        Data $trending,
        array $data = []
    ) {
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
        $om           = ObjectManager::getInstance();
        $storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore = $storeManager->getStore();
        return $currentStore->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    public function getPercentageDonation()
    {
        return $this->_scopeConfig->getValue(
            'donation/general/percentage',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getPercentagePhotographer()
    {
        return $this->_scopeConfig->getValue(
            'marketplace/general_settings/percent',
            ScopeInterface::SCOPE_STORE
        );
    }
}
