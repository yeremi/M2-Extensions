<?php
/**
 * User: Amit Bera
 */

namespace Klloom\Productlike\Model;


use Magento\Framework\Model\AbstractModel;

class Productlike extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Klloom\Productlike\Model\ResourceModel\Productlike::class);
    }
}