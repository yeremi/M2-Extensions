<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 23/09/18
 * Time: 13:14
 */

namespace Klloom\Trending\Block;

/**
 * Class Data
 * @package Klloom\Trending\Block
 */
class Data extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;
    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;
    /**
     * @var array
     */
    private $abuseIDs = [];
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var \Klloom\Trending\Model\ResourceModel\Trending\Collection
     */
    private $trendingCollectionFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Klloom\Trending\Model\ResourceModel\Trending\CollectionFactory $trendingCollectionFactory
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Klloom\Trending\Model\ResourceModel\Trending\CollectionFactory $trendingCollectionFactory,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->productCollectionFactory  = $productCollectionFactory;
        $this->trendingCollectionFactory = $trendingCollectionFactory;
        $this->productStatus             = $productStatus;
        $this->productVisibility         = $productVisibility;
    }

    /**
     * @param string $type
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getTrendingProducts($type = 'all')
    {
        $productIds = $this->getProductsIdsByRanking();
        return $this->getProductCollectionOrderByRankingPosition($productIds, $type);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductsIdsByRanking()
    {
        $collection = $this->trendingCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->getSelect();

        $array = [];
        if (!$collection) {
            return false;
        }

        /**
         * Computed rows collection by product_id
         * We will normalize the values to 1 if has some value, because the next process will be the sum.
         */
        foreach ($collection as $row) {
            if ($row['action_comment'] != 0) {
                $array[$row['product_id']]['comment'][] = 1;
            }
            if ($row['action_like'] != 0) {
                $array[$row['product_id']]['like'][] = 1;
            }
            if ($row['action_view'] != 0) {
                $array[$row['product_id']]['view'][] = 1;
            }
            if ($row['action_friend'] != 0) {
                $array[$row['product_id']]['friend'][] = 1;
            }
            if ($row['action_share'] != 0) {
                $array[$row['product_id']]['share'][] = 1;
            }
            if ($row['action_buy'] != 0) {
                $array[$row['product_id']]['buy'][] = 1;
            }
            if ($row['action_report'] != 0) {
                $array[$row['product_id']]['abuse'][] = 1;
                // Store ids with abuse registered
                $this->abuseIDs[] = $row['product_id'];
            }
        }

        // Remove the product_id from array who has an abuse reported.
        foreach ($array as $key => $value) {
            if (array_key_exists('abuse', $value)) {
                unset($array[$key]);
            }
        }

        /**
         * Sum array by action, this will return something like this
         *
         * [12] => Array (
         *      [comment] => 1
         *      [like] => 0
         *      [view] => 11
         *      [friend] => 0
         *      [share] => 0
         *      ...
         * )
         * ...where 12 represents the product_id
         */
        $out = [];
        foreach ($array as $key => $value) {
            $out[$key] = $this->computingTrending($value);
        }
        arsort($out);

        /**
         * [12] => 7
         * [15] => 4.5
         * [8] => 4
         * [6] => 3
         * [16] => 1
         * [11] => 1
         */
        $productIds = array_keys($out);
        return $productIds;
    }

    /**
     * Computing trending:
     *
     *   0.5 represent 1 view
     *   1.0 represent 1 like
     *   1.5 represent 1 comment
     *   2.0 represent 1 send to a friend
     *   2.0 represent 1 share to social network
     *   3.0 represent 1 buy
     *  ----
     *  10.0 -> Represent a complete cycle
     * - 6.0 represent 1 abuse
     *
     * @param $value
     * @return float|int
     */
    private function computingTrending($value)
    {
        $view    = isset($value['view']) ? array_sum($value['view']) / 2 : 0;
        $like    = isset($value['like']) ? array_sum($value['like']) : 0;
        $comment = isset($value['comment']) ? array_sum($value['comment']) * 1.5 : 0;
        $friend  = isset($value['friend']) ? array_sum($value['friend']) * 2 : 0;
        $share   = isset($value['share']) ? array_sum($value['share']) * 2 : 0;
        $buy     = isset($value['buy']) ? array_sum($value['buy']) * 3 : 0;
        //$abuse   = isset($value['abuse']) ? array_sum($value['abuse']) : 0;

        $points = $view + $like + $comment + $friend + $share + $buy;
        /*if (count($abuse) > 0) {
            $points = $points - ($abuse * 6);
        }*/
        return $points; // < 1 ? 0 : $points;
    }

    /**
     * @param $productIds came from klloom_trending table
     * @param $type
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    function getProductCollectionOrderByRankingPosition($productIds, $type)
    {
        $trendingIds = array_unique($productIds);
        $reportedIds = array_unique($this->abuseIDs);

        if (count($reportedIds) > 0) {
            $orderByIds = array_merge($trendingIds, $reportedIds);
        } else {
            $orderByIds = array_merge($trendingIds);
        }

        $allCollection = $this->productCollectionFactory->create();
        $allCollection->addAttributeToSelect('*');
        $allCollection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $allCollection->addFieldToFilter('entity_id', ['in' => $orderByIds]);
        $allCollection->getSelect()->order("find_in_set(entity_id,'" . implode(',', $orderByIds) . "')");
        //$allCollection->getSelect()->order('created_at DESC');
        $allCollection->setPageSize($type == 'trending' ? 32 : 1500);
        return $allCollection;

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

}