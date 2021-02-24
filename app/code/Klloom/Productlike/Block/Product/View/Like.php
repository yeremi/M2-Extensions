<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 07/09/18
 * Time: 13:28
 */

namespace Klloom\Productlike\Block\Product\View;

use Magento\Framework\View\Element\Template;

class Like extends Template
{
    /**
     * @var \Context
     */
    private $context;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory
     */
    private $likesCollectionFactory;
    /**
     * @var \Klloom\Productlike\Helper\Dataurl
     */
    private $likeHelper;

    /**
     * Like constructor.
     * @param Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Registry $registry
     * @param \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $likesCollectionFactory
     * @param \Klloom\Productlike\Helper\Dataurl $likeHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $registry,
        \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $likesCollectionFactory,
        \Klloom\Productlike\Helper\Dataurl $likeHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->context                = $context;
        $this->customerSession        = $customerSession;
        $this->registry               = $registry;
        $this->likesCollectionFactory = $likesCollectionFactory;
        $this->likeHelper             = $likeHelper;
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function isLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getLikeUrl()
    {
        if ($this->isLoggedIn())
            return $this->likeHelper->getLikeUrl($this->getCurrentProduct()->getId());
    }

    public function isLiked()
    {
        $product_id = $this->getCurrentProduct()->getId();
        $liked      = false;
        $uid        = null;
        if ($this->isLoggedIn()) {
            $uid = $this->customerSession->getCustomerId();
        }
        if ($uid) {
            $collection = $this->likesCollectionFactory->create();
            $collection->addFieldToFilter('product_id', $product_id);
            $collection->addFieldToFilter('customer_id', $uid);
            $liked = $collection->count() ? true : false;
        }
        return $liked;
    }

    public function totalProductLikes()
    {
        $product_id = $this->getCurrentProduct()->getId();
        $collection = $this->likesCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $product_id);
        return $collection->count();
    }
}