<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 28/09/18
 * Time: 10:25
 */

namespace Klloom\Photo\Block\Product\ProductList\Item;

class Block extends \Magento\Framework\View\Element\Template
{

    /**
     * Block constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    function _prepareLayout()
    {
    }

    public function isPurchased()
    {
        if ($this->getParentBlock()->getProduct()) {
            $blockObj  = $this->getLayout()->createBlock('Klloom\Photo\Block\Purchased');
            $productId = $this->getParentBlock()->getProduct()->getId();
            return $blockObj->isPurchased($productId);
        }
    }

    public function isInCartSession()
    {
        if ($this->getParentBlock()->getProduct()) {
            $productId = $this->getParentBlock()->getProduct()->getId();
            $blockObj  = $this->getLayout()->createBlock('Klloom\Photo\Block\Purchased');
            return $blockObj->isInCartSession($productId);
        }
    }

}