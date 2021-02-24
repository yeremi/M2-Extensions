<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/07/18
 * Time: 11:03 AM
 */

namespace Klloom\AbuseReport\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Klloom_AbuseReport';
        $this->_headerText = __('Reports');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}