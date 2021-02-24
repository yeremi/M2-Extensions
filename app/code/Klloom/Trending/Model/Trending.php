<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 23/09/18
 * Time: 13:20
 */

namespace Klloom\Trending\Model;

class Trending extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Klloom\Trending\Model\ResourceModel\Trending');
    }
}