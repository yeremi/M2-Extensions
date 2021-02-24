<?php

namespace Klloom\ProductComments\Block\Product\ProductList\Item;

use Magento\Catalog\Block\Product\AwareInterface as ProductAwareInterface;

class CommentLikeContainer extends \Magento\Catalog\Block\Product\ProductList\Item\Block
{
    /**
     * {@inheritdoc}
     * @since 101.0.1
     */
    public function getChildHtml($alias = '', $useCache = false)
    {
        $layout = $this->getLayout();
        if ($layout) {
            $name = $this->getNameInLayout();
            foreach ($layout->getChildBlocks($name) as $child) {
                if ($child instanceof ProductAwareInterface) {
                    $child->setProduct($this->getProduct());
                }
            }
        }
        return parent::getChildHtml($alias, $useCache);
    }
}