<?php

namespace Klloom\RemoveWrappingParagraphs\Model\Observer\Cms;

use Klloom\RemoveWrappingParagraphs\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class PageRender
 * @package Klloom\RemoveWrappingParagraphs\Model
 */
class PageRender implements ObserverInterface
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
        /** @var \Magento\Cms\Model\Page $page */
        $page    = $observer->getPage();
        $content = $page->getContent();
        $content = $this->helper->removeWrappingParagraphs($content);
        $page->setContent($content);
    }
}