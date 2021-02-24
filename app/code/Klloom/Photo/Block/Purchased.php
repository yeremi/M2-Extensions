<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 23/08/18
 * Time: 17:30
 */

namespace Klloom\Photo\Block;

class Purchased extends \Magento\Framework\View\Element\Template
{

    protected $_coreRegistry = null;
    protected $currentCustomer;
    protected $session;
    protected $httpContext;
    protected $connection;
    protected $_resource;
    private   $checkoutSession;
    private   $storeManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Customer\Model\Session $session,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    )
    {
        $this->currentCustomer = $currentCustomer;
        $this->_coreRegistry   = $registry;
        $this->_resource       = $resource;
        $this->session         = $session;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager    = $context->getStoreManager();
        $this->httpContext     = $httpContext;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->_resource->getConnection('core_write');
        }
        return $this->connection;
    }

    /**
     * @param $productId
     * @return bool
     */
    public function isPurchased($productId)
    {
        $sales_order      = $this->_resource->getTableName('sales_order');
        $sales_order_item = $this->_resource->getTableName('sales_order_item');
        $customer_id      = $this->getCustomer();

        if (!$customer_id) {
            return false;
        }

        $sql     = <<<SQL
SELECT soi.order_id, so.increment_id, so.customer_id, soi.product_id, soi.name 
FROM {$sales_order} AS so 
JOIN {$sales_order_item} AS soi 
ON  soi.order_id = so.entity_id 
AND soi.product_id = {$productId} 
AND so.customer_id = {$customer_id} 
AND so.status='complete' 
AND so.state = 'complete';
SQL;
        $reponse = $this->getConnection()->fetchOne($sql);
        return (boolean)$reponse;
    }

    public function isInCartSession($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Session')->getQuote();
        $result = $cart->getAllVisibleItems();
        $itemsIds = array();
        $c = [];
        foreach ($result as $cartItem) {
            array_push($itemsIds, $cartItem->getProduct()->getId());
            $c[] = $cartItem->getProduct()->getId();
        }

        //return [$c, $productId];
        return (boolean)in_array($productId, $itemsIds);
    }

    public function getCustomer()
    {
        $customer_id = $this->session->getCustomerId();
        if ($this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH)) {
            $customer_id = $this->currentCustomer->getCustomerId();
        }
        return $customer_id;
    }

}