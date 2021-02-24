<?php

namespace Klloom\ProductComments\Block\Product\ProductList\Item\CommentLikeContainer;


class Renderer extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * @var \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory
     */
    protected $commentCollectionFactory;
    /**
     * @var \Webkul\Marketplace\Helper\Data
     */
    protected $marketPlaceHelper;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory $commentCollectionFactory,
        \Webkul\Marketplace\Helper\Data $marketPlaceHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->marketPlaceHelper = $marketPlaceHelper;
    }


    public function getCommentCollection()
    {
        if ($this->getProduct()) {

            $collection = $this->commentCollectionFactory->create();
            $collection->addSellerDataWithStoreFilter();
            $collection->addFieldToSelect('*')
                ->addFieldToFilter('product_id', (int)$this->getProduct()->getId());
            $collection->setOrder('comment_id', 'DESC');
            $collection->setPageSize(3)->setCurPage(1);
            return $collection;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getProductTags()
    {
        $tags = explode(",", $this->getProduct()->getTags());
        return $tags;
    }
    /*
     * Get Seller Profile logo
     */
    public function AvatarLogoPic($comment)
    {

        if ($comment->getSellerLogoPic()) {

            return $this->marketPlaceHelper->getMediaUrl() . 'avatar/thumb' . $comment->getSellerLogoPic();
        }

        return $this->getViewFileUrl('images/avatar/no-image.svg');

    }
}