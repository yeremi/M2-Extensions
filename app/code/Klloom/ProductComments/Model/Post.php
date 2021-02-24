<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/28/18
 * Time: 9:43 AM
 */

namespace Klloom\ProductComments\Model;

use Klloom\ProductComments\Model\ResourceModel\Post as Posts;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(Posts::class );
    }
}