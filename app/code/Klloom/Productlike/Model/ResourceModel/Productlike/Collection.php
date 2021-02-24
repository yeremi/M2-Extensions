<?php

namespace Klloom\Productlike\Model\ResourceModel\Productlike;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected function _construct()
    {
        $this->_init(\Klloom\Productlike\Model\Productlike::class,
            \Klloom\Productlike\Model\ResourceModel\Productlike::class);
    }

}