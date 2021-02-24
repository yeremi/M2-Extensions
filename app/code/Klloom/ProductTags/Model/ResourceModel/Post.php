<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 2019-01-31
 * Time: 23:02
 */

namespace Klloom\ProductTags\Model\ResourceModel;

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
        $this->_init('klloom_crosstags_hidden', 'entity_id');
    }
}