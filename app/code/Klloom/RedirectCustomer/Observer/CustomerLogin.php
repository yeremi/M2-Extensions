<?php

namespace Klloom\RedirectCustomer\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class CustomerLogin
 * @package Klloom\RedirectCustomer\Observer
 */
class CustomerLogin implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Zend\Validator\Uri
     */
    protected $uri;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $responseFactory;

    /**
     * CustomerLogin constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Zend\Validator\Uri $uri
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Zend\Validator\Uri $uri,
        \Magento\Framework\App\ResponseFactory $responseFactory
    )
    {
        $this->scopeConfig     = $scopeConfig;
        $this->uri             = $uri;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $websites = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES;

        $particular_page = $this->scopeConfig->getValue('customer/startup/redirect_particular_page', $websites);

        if ($particular_page == null) {
            $particular_page = $this->scopeConfig->getValue('customer/startup/redirect_particular_page');
        }

        if ($this->uri->isValid($particular_page)) {
            $resultRedirect = $this->responseFactory->create();
            $resultRedirect->setRedirect($particular_page)->sendResponse('200');
            exit();
        }
    }
}