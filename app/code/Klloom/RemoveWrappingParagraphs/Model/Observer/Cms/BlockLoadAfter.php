<?php

namespace Klloom\RemoveWrappingParagraphs\Model\Observer\Cms;

use Klloom\RemoveWrappingParagraphs\Helper\Data;
use Magento\Cms\Model\Block;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class BlockLoadAfter
 * @package Klloom\RemoveWrappingParagraphs\Model
 */
class BlockLoadAfter implements ObserverInterface
{
    /** @var Data */
    protected $helper;

    /**
     * Observer constructor.
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Block $block */
        $block   = $observer->getObject();
        $content = $block->getContent();
        $content = $this->helper->removeWrappingParagraphs($content);
        $block->setContent($content);
    }
}