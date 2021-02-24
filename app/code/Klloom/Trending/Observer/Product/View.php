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

class View implements ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var \Magento\Framework\Session\SidResolver
     */
    private $sidResolver;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;
    /**
     * @var \Klloom\Trending\Model\TrendingFactory
     */
    private $trendingFactory;

    /**
     * View constructor.
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\Session\SidResolver $sidResolver
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Klloom\Trending\Model\TrendingFactory $trendingFactory
     */
    function __construct(
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Session\SidResolver $sidResolver,
        \Magento\Framework\App\Http\Context $httpContext,
        \Klloom\Trending\Model\TrendingFactory $trendingFactory
    )
    {
        $this->session         = $session;
        $this->sidResolver     = $sidResolver;
        $this->httpContext     = $httpContext;
        $this->trendingFactory = $trendingFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product    = $observer->getProduct();
        $product_id = $product->getId();
        $isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);

        if ($isLoggedIn) {
            $customer_id = $this->getCustomer()->getId();
            // TODO Add view once time by SESSSID
            $model = $this->trendingFactory->create();
            $model->setData('customer_id', $customer_id);
            $model->setData('product_id', $product_id);
            $model->setData('action_view', 1);
            $model->save();
            unset($model);
        }

    }

    public function getCustomer()
    {
        return $this->session->getCustomer();
    }

    public function getSidSession()
    {
        return $this->sidResolver->getUseSessionVar();
    }
}