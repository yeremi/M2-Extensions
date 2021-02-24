<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/06/18
 * Time: 9:13 AM
 */

namespace Klloom\AbuseReport\Model\ResourceModel;


class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('klloom_reporting_abuse', 'entity_id');
    }
}