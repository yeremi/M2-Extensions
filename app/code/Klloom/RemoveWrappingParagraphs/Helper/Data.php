<?php

namespace Klloom\RemoveWrappingParagraphs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * @package Klloom\RemoveWrappingParagraphs\Helper
 */
class Data extends AbstractHelper
{
    /**
     * Remove wrapping <p></p> tags around widgets
     *
     * @param $content
     * @return mixed
     */
    public function removeWrappingParagraphs($content)
    {
        // Remove wrapping paragraphs around widgets:
        $content = preg_replace('/<p>{{(.*?)}}<\/p>/', '{{$1}}', $content);

        // Remove div around widgets
        $content = preg_replace('/<div>{{(.*?)}}<\/div>/', '{{$1}}', $content);

        // Remove empty paragraphs:
        $content = preg_replace('/<p>(|s*|&nbsp;|n)<\/p>/', '', $content);

        return $content;
    }
}