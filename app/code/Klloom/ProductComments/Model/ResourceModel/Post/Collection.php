<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/28/18
 * Time: 9:15 AM
 */

namespace Klloom\ProductComments\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = 'comment_id';
    protected $_eventPrefix = 'klloom_productcomments_post_collection';
    protected $_eventObject = 'post_collection';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Klloom\ProductComments\Model\Post', 'Klloom\ProductComments\Model\ResourceModel\Post');
    }

    /**
     *  Add seller table to Collection
     * @param null $storeId
     */
    public function addSellerDataWithStoreFilter($storeId = null)
    {
        $sellerTable = $this->getTable('marketplace_userdata');
        if($storeId == null ) {
            $storeId = $this->storeManager->getStore()->getId();
        }
        $this->_select->joinLeft(
            ['seller' => $sellerTable],
            implode(
                ' AND ',
                [
                    'main_table.customer_id = seller.seller_id',
                    $this->getConnection()->quoteInto('seller.store_id = ?', (int)$storeId)
                ]),
            ['seller_id' => 'seller_id','seller_logo_pic' => 'logo_pic','seller_shop_title' => 'shop_title']
        );

    }
    public function addTotalCount()
    {
        $countCond = new \Zend_Db_Expr('COUNT(customer_id)');

        $this->addExpressionFieldToSelect(
            'total_count',
                $countCond,
                'product_id'
        );
        $this->getSelect()->group('seller.seller_id');
    }


}