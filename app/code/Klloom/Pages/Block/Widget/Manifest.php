<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 15/07/18
 * Time: 17:10
 */

namespace Klloom\Pages\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Manifest extends Template implements BlockInterface
{
    protected $_template = "widget/manifest.phtml";
}