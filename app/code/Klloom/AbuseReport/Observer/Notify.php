<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 21/11/18
 * Time: 16:50
 */

namespace Klloom\AbuseReport\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Notify
 * @package Klloom\AbuseReport\Observer
 */
class Notify implements ObserverInterface
{

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    private $customers;
    /**
     * @var \Klloom\AbuseReport\Model\MailInterface
     */
    private $mail;

    /**
     * Notify constructor.
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Customer $customers
     * @param \Klloom\AbuseReport\Model\MailInterface $mail
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer $customers,
        \Klloom\AbuseReport\Model\MailInterface  $mail
    )
    {
        $this->productRepository = $productRepository;
        $this->storeManager      = $storeManager;
        $this->customers         = $customers;
        $this->mail = $mail;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $data         = $observer->getData('klloom_event');
        $currentStore = $this->storeManager->getStore();
        $sellerResult = $this->getSellerIdByProduct($data['product_id']);
        $mediaUrl     = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if ($sellerResult AND isset($sellerResult[0]['seller_id'])) {

            $_photographer = $this->getCustomer($sellerResult[0]['seller_id']);
            $_product      = $this->getProduct($data['product_id']);

            if (!$_product) {
                $this->messageManager->addErrorMessage('Product doesn\'t exist.');
                return;
            }

            $templateVars = [
                'report_type' => $data['report'],
                'photo_name'  => $_product->getName(),
                'photo_url'   => $_product->getProductUrl(),
                'photo_src'   => $mediaUrl . 'catalog/product' . $_product->getData('image')
            ];

            $to = $_photographer->getEmail();

            /**
             * TODO
             * Send email to the owner of the photo ($to)
             * template id="notify_email_abuse_email_template"
             * Don't add reply_to
             */
            $this->mail->abuseNotifyMailSend($to,$templateVars);
        }

    }

    /**
     * @param $productId
     * @return mixed
     * TODO Need to do better !!!
     */
    protected function getSellerIdByProduct($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource      = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection    = $resource->getConnection();
        $tableName     = $resource->getTableName('marketplace_product');
        $sql           = "SELECT seller_id FROM " . $tableName . " WHERE mageproduct_id = " . $productId;
        return $connection->fetchAll($sql);
    }

    /**
     * @param $customerId
     * @return bool
     */
    protected function getCustomer($customerId)
    {
        try {
            $customers = $this->customers->load($customerId);
            return $customers;
        } catch (\Magento\Framework\Exception\NoSuchEntityException  $exception) {
            return false;
        }
        return false;
    }

    /**
     * @param $productId
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    protected function getProduct($productId)
    {
        try {
            $_product = $this->productRepository->getById($productId);
            return $_product;
        } catch (\Magento\Framework\Exception\NoSuchEntityException  $exception) {
            return false;
        }
        return false;

    }

}