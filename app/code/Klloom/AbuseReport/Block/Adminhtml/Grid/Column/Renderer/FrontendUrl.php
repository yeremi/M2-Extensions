<?php
/**
 * User: Amit Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Klloom\AbuseReport\Block\Adminhtml\Grid\Column\Renderer;


class FrontendUrl  extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

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

        $product         = $this->productRepository->getById($row->getData('product_id'),false,1);
        $linkToStop      = '';
        $linkDoNotReport = '';

        if($row->getData('stop_by_abuse') == 0){
            $actionUrl  = $this->getUrl('klloom_abusereport/post/stopphoto',[
                'stop_by_abuse' => 1,
                'pkid' => $row->getData('entity_id')
            ]);
            $linkToStop = '<td style="border: none"></td><td style="border: none"><a href="' . $actionUrl . '">' . __('Report') . '</a> <p style="font-size: 1.2rem; color: #adadad; width: 160px">Init report process, starting with sending email to photographer.</p></td>';

            $actionUrlDoNotReport = $this->getUrl('klloom_abusereport/post/donotreport', [
                'pkid'       => $row->getData('entity_id'),
                'product_id' => $row->getData('product_id')
            ]);
            $linkDoNotReport      = '<td style="border: none"></td><td style="border: none"><a href="' . $actionUrlDoNotReport . '">' . __('Dismiss') . '</a> <p style="font-size: 1.2rem; color: #adadad; width: 160px">Remove photo from<br />Abuse Report list</p></td>';
        }

        $html = '<table class="tg">
                  <tr>

                    '.$linkToStop.'
                    ' . $linkDoNotReport . '
                  </tr>
                </table>';

        return $html;
    }
}