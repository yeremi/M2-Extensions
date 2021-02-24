<?php
/**
 * User: Amit Bera
 */

namespace Klloom\Productlike\Controller\Customer;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;

class Save extends Action
{
    /**
     * @var \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    /**
     * @var \Klloom\Productlike\Model\Productlike
     */
    protected $productlike;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    protected $dateTimeFactory;
    /**
     * @var \Klloom\Productlike\Model\ProductlikeFactory
     */
    private $productlikeFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param \Magento\Customer\Model\Session $session
     * @param \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $collectionFactory
     * @param \Klloom\Productlike\Model\ProductlikeFactory $productlikeFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory
     * @param RequestInterface $request
     */

    public function __construct(Context $context,
        \Magento\Customer\Model\Session $session,
        \Klloom\Productlike\Model\ResourceModel\Productlike\CollectionFactory $collectionFactory,
        \Klloom\Productlike\Model\ProductlikeFactory $productlikeFactory,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->session = $session;
        $this->request = $request;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->productlikeFactory = $productlikeFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {

        if (!$this->session->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
            if (!$this->session->getBeforeUrl()) {
                $this->session->setBeforeUrl($this->_redirect->getRefererUrl());
            }
        }
        return parent::dispatch($request);
    }

    public function execute()
    {

        $productlikeCollection = $this->collectionFactory->create();
        $productlikeCollection->addFieldToSelect('*');
        $productlikeCollection->addFieldToFilter('customer_id', $this->session->getCustomerId());
        $productlikeCollection->addFieldToFilter('product_id', $this->getRequest()->getParam('product_id'));
        $productlikeCollection->setCurPage(1)->setPageSize(1);

        $productlikemodel = $this->productlikeFactory->create();

        if ($productlikeCollection->count() >= 1) {
            $firstitem = $productlikeCollection->getFirstItem();
            $firstid = $firstitem->getId();
            $productlikemodel->load($firstid);
            $productlikemodel->delete();

            // <--- START Klloom Event Manager for Trending --->
            $data = array(
                'customer_id' => $this->session->getCustomerId(),
                'product_id'  => $this->getRequest()->getParam('product_id'),
                'action_like' => $firstid,
                'method'      => 'delete'
            );
            $this->_eventManager->dispatch('klloom_trending_events', array('klloom_event' => $data));
            // <--- END --->

            return;
        }

        $this->getRequest()->getParam('product_id');

        $productlikemodel->setData('product_id', $this->getRequest()->getParam('product_id'));
        $productlikemodel->setData('customer_id', $this->session->getCustomerId());
        try {
            $productlikemodel->save();

            // <--- START Klloom Event Manager for Trending --->
            $data = array(
                'customer_id' => $this->session->getCustomerId(),
                'product_id'  => $this->getRequest()->getParam('product_id'),
                'action_like' => $productlikemodel->getId(),
                'method'      => 'insert'
            );
            $this->_eventManager->dispatch('klloom_trending_events', array('klloom_event' => $data));
            // <--- END --->

        } catch (\Exception $exception) {
            //$this->messageManager->addError($exception->getMessage());
            // TODO we need to think a way to store errors log and then report for fixing.
        }
    }
}