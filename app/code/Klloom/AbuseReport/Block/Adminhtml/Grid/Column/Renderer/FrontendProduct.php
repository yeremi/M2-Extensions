<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 19/11/18
 * Time: 18:46
 */

namespace Klloom\AbuseReport\Block\Adminhtml\Grid\Column\Renderer;

class FrontendProduct extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
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
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->productRepository = $productRepository;
    }

    public function _getValue(\Magento\Framework\DataObject $row)
    {
        $html     = '';

        if (!empty($row->getData('sku'))) {

            $product = $this->productRepository->get($row->getData('sku'));
            $html = '<a target="_blank" href="' . $product->getProductUrl() . '" title="' . $product->getName() . '">' . $product->getName() . '</a>';

        }
        return $html;
    }
}