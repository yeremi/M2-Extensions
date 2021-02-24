<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 12/11/18
 * Time: 17:05
 */

namespace Klloom\Trending\Block;

use Magento\Framework\View\Element\Template;

class All extends Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }
}