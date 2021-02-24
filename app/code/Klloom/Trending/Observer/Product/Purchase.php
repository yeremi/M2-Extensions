<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 26/09/18
 * Time: 11:24
 */

namespace Klloom\Trending\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Purchase implements ObserverInterface
{

    /**
     * @var \Klloom\Trending\Model\TrendingFactory
     */
    private $trendingFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $_salesOrderCollection;

    /**
     * Purchase constructor.
     * @param \Klloom\Trending\Model\TrendingFactory $trendingFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection
     * @param \Magento\Customer\Model\Session $session
     */
    public function __construct(
        \Klloom\Trending\Model\TrendingFactory $trendingFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \Magento\Customer\Model\Session $session
    )
    {

        $this->trendingFactory       = $trendingFactory;
        $this->session               = $session;
        $this->_salesOrderCollection = $salesOrderCollection;
    }

    /**
     * @param Observer $observer
     * @return Purchase
     */
    public function execute(Observer $observer)
    {
        $customer_id = $this->getCustomer()->getId();

        $orderIds = $observer->getEvent()->getOrderIds();
        if (!$orderIds || !is_array($orderIds)) {
            return $this;
        }
        $collection = $this->_salesOrderCollection->create();
        $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);
        foreach ($collection as $order) {
            foreach ($order->getAllVisibleItems() as $item) {
                $model = $this->trendingFactory->create();
                $model->setData('customer_id', $customer_id);
                $model->setData('product_id', $item->getProductId());
                $model->setData('action_buy', 1);
                $model->save();
                unset($model);
            }
        }
    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }
}