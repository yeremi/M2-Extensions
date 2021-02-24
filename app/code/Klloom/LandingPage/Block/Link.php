<?php

namespace Klloom\LandingPage\Block;


class Link extends \Magento\Framework\View\Element\Html\Link
{
    protected $_logo;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        array $data = []
    )
    {
        $this->_logo = $logo;
        parent::__construct($context, $data);
    }

    public function isHomePage()
    {
        return $this->_logo->isHomePage();
    }

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
        if($this->isHomePage()){
            return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a>';
        }

    }

}