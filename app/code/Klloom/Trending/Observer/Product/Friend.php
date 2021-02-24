<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 21/09/18
 * Time: 08:23
 */

namespace Klloom\Trending\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Friend implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Klloom\Trending\Model\TrendingFactory
     */
    private $trendingFactory;

    /**
     * Friend constructor.
     * @param \Magento\Customer\Model\Session $session
     * @param \Klloom\Trending\Model\TrendingFactory $trendingFactory
     */
    function __construct(
        \Magento\Customer\Model\Session $session,
        \Klloom\Trending\Model\TrendingFactory $trendingFactory
    )
    {
        $this->session         = $session;
        $this->trendingFactory = $trendingFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product     = $observer->getProduct();
        $product_id  = $product->getId();
        $customer_id = $this->getCustomer()->getId();

        $model = $this->trendingFactory->create();
        $model->setData('customer_id', $customer_id);
        $model->setData('product_id', $product_id);
        $model->setData('action_friend', 1);
        $model->save();
        unset($model);

    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }
}