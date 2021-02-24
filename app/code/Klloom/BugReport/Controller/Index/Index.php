<?php

namespace Klloom\BugReport\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Klloom\BugReport\Controller\Index
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}