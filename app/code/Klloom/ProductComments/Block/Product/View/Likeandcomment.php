<?php
namespace Klloom\ProductComments\Block\Product\View;


use Magento\Catalog\Block\Product\View;

class Likeandcomment extends View
{

    public function getProductCommentLikeHtml(
        \Magento\Catalog\Model\Product $product
    )
    {

        $commentandlikeRenderBlock = $this->getLayout()
            ->createBlock(\Klloom\ProductComments\Block\Product\ProductList\Item\CommentLikeContainer\Renderer::class,
                '',
                [ 'product' =>$product]
            )
            ->setTemplate('Klloom_ProductComments::product/list/comment-like-container.phtml')
            ->setProduct($product);

        $likeBlock = $this->getLayout()
            ->createBlock(\Klloom\Productlike\Block\Product\ProductList\Item\Countlink::class)
            ->setTemplate('Klloom_Productlike::product/list/like.phtml');
        $commentandlikeRenderBlock->setChild('likescount',$likeBlock);
        return $commentandlikeRenderBlock->toHtml();

    }

    /** Render Html for Comment and like
     * @return string
     */
    protected function _toHtml()
    {
        $html = $this->getProductCommentLikeHtml($this->getProduct());
        return $html;
    }
}