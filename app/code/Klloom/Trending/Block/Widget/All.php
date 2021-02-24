<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 16/10/18
 * Time: 11:26
 */

namespace Klloom\Trending\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class All extends Template implements BlockInterface
{
    protected $_template = "widget/all-photos.phtml";
}