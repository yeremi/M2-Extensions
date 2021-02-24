<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/28/18
 * Time: 9:15 AM
 */

namespace Klloom\ProductComments\Model\ResourceModel;


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
        $this->_init('klloom_comments', 'comment_id');
    }
}