<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 23/09/18
 * Time: 13:20
 */

namespace Klloom\Trending\Model\ResourceModel;

class Trending extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /*public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }*/

    protected function _construct()
    {
        $this->_init('klloom_trending', 'entity_id');
    }
}