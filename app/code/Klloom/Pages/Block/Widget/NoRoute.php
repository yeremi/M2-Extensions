<?php

namespace Klloom\Pages\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class NoRoute extends Template implements BlockInterface
{
    protected $_template = "widget/no-route.phtml";
}