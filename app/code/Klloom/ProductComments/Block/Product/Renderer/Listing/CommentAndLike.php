<?php
/**
 * Created by Amit Bera.
 * User: Amit Kumar Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Klloom\ProductComments\Block\Product\Renderer\Listing;

use Magento\Framework\View\Element\Template;

class CommentAndLike  extends \Magento\Framework\View\Element\Template
{
    protected $saleableItem;

    /**
     * @var \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory
     */
    protected $commentCollectionFactory;

    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('Klloom_ProductComments::product/list/comment-like.phtml');
    }

    public function __construct(
        Template\Context $context,
        \Klloom\ProductComments\Model\ResourceModel\Post\CollectionFactory $commentCollectionFactory,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->commentCollectionFactory = $commentCollectionFactory;
    }


    /*
     * Comment Collection of a product
     * @return false / Klloom\ProductComments\Model\ResourceModel\Post\Collection
     */
    public function getCommentCollection()
    {
        if ($this->getProductItemData()) {

            $collection = $this->commentCollectionFactory->create();
            $collection->addSellerDataWithStoreFilter($this->getStoreId());
            $collection->addFieldToSelect('*')
                ->addFieldToFilter('product_id', (int)$this->getProductItemData()->getId())
                ->addTotalCount();
            $collection->setOrder('comment_id','DESC');
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
        $tags=(explode(",",$this->getProductItemData()->getTags()));
        return $tags;
    }
}