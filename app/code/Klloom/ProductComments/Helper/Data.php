<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 01/07/18
 * Time: 15:34
 */

namespace Klloom\ProductComments\Helper;

use DateTime;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
        $this->request = $request;
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'h',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function avatar($avatar = null)
    {
        if ($avatar) {
            $logoPic = str_replace('.', '-small.', $avatar);
            return $this->getMediaUrl() . 'avatar/thumb' . $logoPic;
        } else {
            return $this->assetRepo->getUrl("images/avatar/no-image.svg");
        }
    }

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
    }

    function getMediaBaseUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager  = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $currentStore  = $storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

}