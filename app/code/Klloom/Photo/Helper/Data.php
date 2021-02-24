<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 17/07/18
 * Time: 19:41
 */

namespace Klloom\Photo\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Klloom\Photo\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     *
     */
    const XML_PATH_FOR_PHOTO_LIMIT = 'photo/general/upload_photo_limit';

    /**
     *
     */
    const XML_PATH_FOR_PHOTO_SIZE = 'photo/general/upload_photo_size';

    const XML_PATH_FOR_PHOTO_MIN_SIZE = 'photo/general/upload_photo_min_size';

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
    }

    /**
     * @param $float
     * @return float|int|string
     * @ref https://stackoverflow.com/questions/36642512/get-image-aspect-ratio
     * @ref https://stackoverflow.com/questions/1954018/php-convert-decimal-into-fraction-and-back/9143510#9143510
     */
    function decToFraction($float)
    {
        // 1/2, 1/4, 1/8, 1/16, 1/3 ,2/3, 3/4, 3/8, 5/8, 7/8, 3/16, 5/16, 7/16,
        // 9/16, 11/16, 13/16, 15/16
        $whole            = floor($float);
        $decimal          = $float - $whole;
        $leastCommonDenom = 48; // 16 * 3;
        $denominators     = array(2, 3, 4, 8, 16, 24, 48);
        $roundedDecimal   = round($decimal * $leastCommonDenom) / $leastCommonDenom;
        if ($roundedDecimal == 0)
            return $whole;
        if ($roundedDecimal == 1)
            return $whole + 1;
        foreach ($denominators as $d) {
            if ($roundedDecimal * $d == floor($roundedDecimal * $d)) {
                $denom = $d;
                break;
            }
        }
        return ($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom;
    }

    /**
     * @param $filename
     * @return array
     */
    public function get_dpi($filename)
    {
        // open the file and read first 20 bytes.
        $a      = fopen($filename, 'r');
        $string = fread($a, 20);
        fclose($a);
        // get the value of byte 14th up to 18th
        $data = bin2hex(substr($string, 14, 4));
        $x    = substr($data, 0, 4);
        $y    = substr($data, 4, 4);
        return array(hexdec($x), hexdec($y));
    }

    /**
     * @param $a
     * @param $b
     * @return float|int
     */
    public function gcd($a, $b)
    {
        if ($a == 0 || $b == 0) {
            return abs(max(abs($a), abs($b)));
        }

        $r = $a % $b;
        return ($r != 0) ? $this->gcd($b, $r) : abs($b);
    }

    /**
     * @param $bytes
     * @return float|int|string
     */
    function fileSizeConvert($bytes)
    {
        $bytes   = floatval($bytes);
        $arBytes = array(
            0 => array(
                "unit"  => "TB",
                "value" => pow(1024, 4)
            ),
            1 => array(
                "unit"  => "GB",
                "value" => pow(1024, 3)
            ),
            2 => array(
                "unit"  => "MB",
                "value" => pow(1024, 2)
            ),
            3 => array(
                "unit"  => "KB",
                "value" => 1024
            ),
            4 => array(
                "unit"  => "B",
                "value" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["value"]) {
                $result = $bytes / $arItem["value"];
                $result = str_replace(".", ",", strval(round($result, 0, PHP_ROUND_HALF_UP))) . "" . $arItem["unit"];
                break;
            }
        }
        return $result;
    }

    /**
     * @param $str
     * @return string
     */
    function split($str)
    {
        return str_split( strval($str), 2)[0]."MB";
    }

    /**
     * @return mixed
     */
    public function limitUpload()
    {

        return $this->scopeConfig->getValue(self::XML_PATH_FOR_PHOTO_LIMIT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function uploadMaxFilesize()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_FOR_PHOTO_SIZE, ScopeInterface::SCOPE_STORE);
    }

    public function uploadMinFilesize()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_FOR_PHOTO_MIN_SIZE, ScopeInterface::SCOPE_STORE);
    }
}