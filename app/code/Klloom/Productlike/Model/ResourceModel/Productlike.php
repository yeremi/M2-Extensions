<?php

namespace Klloom\Productlike\Model\ResourceModel;


class Productlike extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('product_like', 'like_id');
    }

}
