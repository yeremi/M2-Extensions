<?php

namespace Klloom\Pages\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class PrivacyPolicy extends Template implements BlockInterface
{
    protected $_template = "widget/privacy-policy.phtml";
}