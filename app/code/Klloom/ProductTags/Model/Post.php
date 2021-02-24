<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 2019-01-31
 * Time: 22:59
 */

namespace Klloom\ProductTags\Model;

use Klloom\ProductTags\Model\ResourceModel\Post as Posts;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(Posts::class );
    }
}