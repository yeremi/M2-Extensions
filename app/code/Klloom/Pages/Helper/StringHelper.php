<?php

namespace Klloom\Pages\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class StringHelper extends AbstractHelper
{
    public function menuActive($string)
    {
        return (strpos($_SERVER['REQUEST_URI'], $string) !== false) ? 'class="is-active"' : '';
    }

    function trim_text($input, $length, $ellipses = true, $strip_html = true)
    {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }
}