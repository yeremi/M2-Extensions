<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 22/08/18
 * Time: 12:11
 */

namespace Klloom\ProductComments\Controller\Ajax;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Registry;

class Delete extends \Magento\Framework\App\Action\Action
{

    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * Index constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Session $customerSession
    )
    {
        parent::__construct($context);
        $this->_registry         = $registry;
        $this->_customerSession  = $customerSession;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {

        if (!$this->getRequest()->isAjax()) {
            $this->_redirect('/');
            return;
        }

        $customerId = $this->_getCustomerSession()->getCustomerId();
        if (!$customerId) {
            $this->_redirect('/');
            return;
        }
        $commentId = $this->getRequest()->getParam('ci');
        $productId = $this->getRequest()->getParam('pi');

        // TODO: Improve this query
        $resource      = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection    = $resource->getConnection();
        $tableComments = $resource->getTableName('klloom_comments');

        $sql = "DELETE FROM " . $tableComments . " 
        WHERE md5(comment_id) = '" . $commentId . "'
        AND md5(product_id) = '" . $productId . "'
        AND customer_id =  " . $customerId;

        $connection->query($sql);

        // <--- START Klloom Event Manager for Trending --->
        $data = array(
            'customer_id'    => $customerId,
            'product_id'     => $productId,
            'action_comment' => $commentId,
            'method'         => 'delete'
        );
        $this->_eventManager->dispatch('klloom_trending_events', array('klloom_event' => $data));
        // <--- END --->
    }

    protected function _getCustomerSession()
    {
        return $this->_customerSession;
    }

}