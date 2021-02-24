<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/06/18
 * Time: 9:13 AM
 */

namespace Klloom\AbuseReport\Model;

use Klloom\AbuseReport\Model\ResourceModel\Post as Posts;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(Posts::class);
    }
}