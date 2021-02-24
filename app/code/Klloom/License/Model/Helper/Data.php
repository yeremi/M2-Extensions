<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 03/07/18
 * Time: 20:28
 * https://webkul.com/blog/get-products-custom-attribute-options-magento2/
 * https://stackoverflow.com/questions/34437283/how-to-get-product-attribute-options-by-attribute-code-in-magento-2-0
 */

namespace Klloom\License\Model\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $attributeManagement;
    protected $productAttributeRepository;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Eav\Model\AttributeManagement $attributeManagement
     * @param \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Eav\Model\AttributeManagement $attributeManagement,
        \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository
    )
    {
        parent::__construct($context);
        $this->attributeManagement        = $attributeManagement;
        $this->productAttributeRepository = $productAttributeRepository;
    }

    /**
     * Get all attributes for an attribute set Id.
     * @param $attributeSetId
     * @return bool
     */
    public function getCustomAttributes($attributeSetId)
    {
        try {
            $groups = $this->attributeManagement->getAttributes(
                \Magento\Catalog\Model\Product::ENTITY,
                $attributeSetId
            );
            foreach ($groups as $node) {
                return $node->getData();
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get an array of options
     * @param $attrCode
     * @return bool|\Magento\Eav\Api\Data\AttributeOptionInterface[]|null
     */
    public function getCatalogResourceEavAttribute($attrCode)
    {
        try {
            return $this->productAttributeRepository->get($attrCode)->getOptions();
        } catch (\Exception $e) {
            return false;
        }
    }

}