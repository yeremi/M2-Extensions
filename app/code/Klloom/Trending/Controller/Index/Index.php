<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 12/11/18
 * Time: 11:53
 */

namespace Klloom\Trending\Controller\Index;

#use Magento\Framework\App\Action\Action;
#use Magento\Framework\View\Result\PageFactory;
#use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    /**
     * All constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        return $this->_pageFactory->create();
    }
}