<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/28/18
 * Time: 8:57 AM
 */

namespace Klloom\ProductComments\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Klloom_ProductComments';
        $this->_headerText = __('Comments');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}