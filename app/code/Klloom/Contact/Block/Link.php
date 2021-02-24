<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 16/07/18
 * Time: 19:27
 */

namespace Klloom\Contact\Block;


class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a>';
    }

}