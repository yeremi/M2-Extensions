<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 19/11/18
 * Time: 09:55
 */

namespace Klloom\AbuseReport\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

/**
 * Class DoNotReport
 * @package Klloom\AbuseReport\Controller\Adminhtml\Post
 */
class DoNotReport extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Api\Data\ProductInterface
     */
    protected $product;

    /**
     * @var \Klloom\AbuseReport\Model\PostFactory
     */
    private $postFactory;
    /**
     * @var \Klloom\Trending\Model\ResourceModel\Trending\CollectionFactory
     */
    private $trendingCollectionFactory;
    /**
     * @var \Klloom\Trending\Model\Trending
     */
    private $trendingModelFactory;

    /**
     * DoNotReport constructor.
     * @param Action\Context $context
     * @param \Klloom\AbuseReport\Model\PostFactory $postFactory
     * @param \Klloom\Trending\Model\ResourceModel\Trending\CollectionFactory $trendingCollectionFactory
     * @param \Klloom\Trending\Model\Trending $trendingModelFactory
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     */
    public function __construct(
        Action\Context $context,
        \Klloom\AbuseReport\Model\PostFactory $postFactory
        //\Klloom\Trending\Model\ResourceModel\Trending\CollectionFactory $trendingCollectionFactory,
        //\Klloom\Trending\Model\TrendingFactory $trendingModelFactory,
        //\Magento\Catalog\Api\Data\ProductInterface $product
    )
    {
        parent::__construct($context);
        $this->postFactory               = $postFactory;
        //$this->product                   = $product;
        //$this->trendingCollectionFactory = $trendingCollectionFactory;
        //$this->trendingModelFactory      = $trendingModelFactory;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        // AbuseReport
        $id         = $this->getRequest()->getParam('pkid');
        $product_id = $this->getRequest()->getParam('product_id');
        $post       = $this->postFactory->create();
        $post->load($id);

        if ($post && ($post->getId() > 0)) {
            try {

                // Remove from AbuseReport
                $post->load($id)->delete();

                // Update trending removing the abuse registered
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                $resource      = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection    = $resource->getConnection();
                $tableName     = $resource->getTableName('klloom_trending');
                $sql           = "UPDATE " . $tableName . " SET action_report = 0 WHERE action_report > 0 AND product_id = '$product_id'";
                $connection->query($sql);

                $this->messageManager->addSuccessMessage('You saved the configuration.');

            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage('Something went wrong.');
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('klloom_abusereport/*/');

    }

}