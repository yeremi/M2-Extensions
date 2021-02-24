<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 18/07/18
 * Time: 11:14
 */

namespace Klloom\Photo\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Model\Product\Option;

#use Psr\Log\LoggerInterface;

class DataExtractor implements ObserverInterface
{

    /**
     * @var \Klloom\Photo\Logger\Logger|LoggerInterface
     */
    protected $_logger;

    /**
     * @var Option
     */
    protected $_options;


    /**
     * DataExtractor constructor.
     * @param \Klloom\Photo\Logger\Logger $loggerInterface
     * @param Option $options
     * @param \Magento\Catalog\Model\ProductFactory $_productloader
     * @param \Magento\Store\Model\StoreManagerInterface $storemanager
     */
    public function __construct(
        \Klloom\Photo\Logger\Logger $loggerInterface,
        Option $options

    )
    {
        $this->_options = $options;
        $this->_logger  = $loggerInterface;

    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {

        $product = $observer->getEvent()->getProduct();

        $productArray = array(
            'ID'   => $product->getId(),
            'Name' => $product->getName(),
            'Url'  => $product->getProductUrl()
        );

        $this->_logger->info(print_r($productArray, true));
    }

}