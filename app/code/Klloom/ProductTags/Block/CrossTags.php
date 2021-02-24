<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 2019-01-30
 * Time: 16:55
 */

namespace Klloom\ProductTags\Block;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;

class CrossTags extends \Magento\Framework\View\Element\Template
{
    public $nickname = '@yeremi';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager;
    /**
     * @var \Klloom\ProductTags\Model\ResourceModel\Post\CollectionFactory
     */
    private $_collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Klloom\ProductTags\Model\ResourceModel\Post\CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_objectManager = $objectManager;

        $this->_collectionFactory = $collectionFactory;
    }

    public function getPhotosByCrossTags()
    {
        return $this->_objectManager->create('Klloom\Trending\Block\Data')->getTrendingProducts('all');
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

    public function getAjaxUrl()
    {
        return $this->getUrl("klloom_producttags/ajax/delete", ['_secure' => $this->getRequest()->isSecure()]);
    }

    public function getHiddenPhotos($profile_id)
    {
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToSelect('*')->addFieldToFilter('profile_id', (int)$profile_id);
        return $collection;
    }
}