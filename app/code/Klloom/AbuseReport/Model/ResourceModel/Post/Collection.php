<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/06/18
 * Time: 9:13 AM
 */

namespace Klloom\AbuseReport\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'klloom_abusereport_post_collection';
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Klloom\AbuseReport\Model\Post', 'Klloom\AbuseReport\Model\ResourceModel\Post');
    }
}