<?php
/**
 * User: Amit Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Klloom\AbuseReport\Controller\Adminhtml\Post;


use Klloom\AbuseReport\Model\Post;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Stopphoto extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Klloom_AbuseReport::post';

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;
    /**
     * @var \Klloom\AbuseReport\Model\PostFactory
     */
    private $postFactory;
    /**
     * @var \Magento\Catalog\Api\Data\ProductInterface
     */
    protected $product;

    public function __construct(
        Action\Context $context,
        \Klloom\AbuseReport\Model\PostFactory $postFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Api\Data\ProductInterface $product
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->postFactory = $postFactory;
        $this->product = $product;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('pkid');
        $post = $this->postFactory->create();
        $post->load($id);
        if ($post && ($post->getId() > 0)) {
            try {
                $post->setData('stop_by_abuse', 1);
                $post->save();

                $product = $this->productRepository->getById($post->getData('product_id'));
                $product->setStatus(2);
                $this->productRepository->save($product);

                $data = array(
                    'product_id'  => $post->getData('product_id'),
                    'report'      => $post->getData('report')
                );

                $this->_eventManager->dispatch('klloom_abuse_events', array('klloom_event' => $data));

                $this->messageManager->addSuccessMessage('You saved the configuration.');

            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage('Something went wrong.');
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('klloom_abusereport/*/');
    }
}