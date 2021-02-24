<?php
namespace Klloom\Productlike\Controller\Customer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session as CustomerSession;


/**
 * User: Amit
 * Date: 3/12/2018
 * Time: 6:38 PM
 */

class Productlikelist extends Action{
    /**
     * @var CustomerSession
     */
    protected $session;


    public function __construct(
        Context $context,
        CustomerSession $session
    )
    {
        parent::__construct($context);
        $this->session = $session;
    }


    /**
     * Prevent the page from not loggedin in customer/Selletr
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
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
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function  execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
     }

}