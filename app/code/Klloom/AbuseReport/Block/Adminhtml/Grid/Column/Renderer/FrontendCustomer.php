<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 11/16/18
 * Time: 12:32 AM
 */

namespace Klloom\AbuseReport\Block\Adminhtml\Grid\Column\Renderer;

use \Webkul\Marketplace\Helper\Data as MarketplaceHelper;

class FrontendCustomer extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;
    /**
     * @var MarketplaceHelper
     */
    private $marketplaceHelper;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        MarketplaceHelper $marketplaceHelper,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
        $this->marketplaceHelper = $marketplaceHelper;
    }

    public function _getValue(\Magento\Framework\DataObject $row)
    {
        $html     = '';
        $shop_url = null;

        if ($row->getData('customer_id')) {
            $rowsocial = $this->marketplaceHelper->getSellerDataBySellerId($row->getData('customer_id'));
            foreach ($rowsocial as $value) {
                $shop_url = $value['shop_url'];
            }
        }

        if (!empty($row->getData('firstname'))) {
            $customer = $row->getData('firstname') . ' ' . $row->getData('lastname');
            if ($shop_url) {
                $html .= '<a href="' . $this->marketplaceHelper->getRewriteUrl('marketplace/seller/profile/shop/' . $shop_url) . '" title="' . $customer . '">' . $customer . '</a>';
            } else {
                $html .= $customer;
            }
        }
        return $html;
    }
}