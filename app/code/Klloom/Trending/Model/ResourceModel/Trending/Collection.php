<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 23/09/18
 * Time: 13:19
 */

namespace Klloom\Trending\Model\ResourceModel\Trending;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init('Klloom\Trending\Model\Trending', 'Klloom\Trending\Model\ResourceModel\Trending');
    }
}