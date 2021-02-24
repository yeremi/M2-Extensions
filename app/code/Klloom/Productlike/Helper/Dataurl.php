<?php
/**
 * User: Amit Bera
 */

namespace Klloom\Productlike\Helper;

use Magento\Framework\App\Helper\Context;

class Dataurl extends \Magento\Framework\Url\Helper\Data
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     *  Create Like Url
     * @param $productId
     * @return string
     */
    public function getLikeUrl($productId)
    {
        return $this->_getUrl(
            'productlike/customer/save', ['product_id' => $productId,
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $this->getEncodedUrl()]
        );

    }
}