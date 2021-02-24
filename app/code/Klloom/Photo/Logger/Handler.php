<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 18/07/18
 * Time: 11:48
 */

namespace Klloom\Photo\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * @var int
     */
    protected $loggerType = \Monolog\Logger::INFO;

    /**
     * @var string
     */
    protected $fileName = '/var/log/klloom_photo.log';
}