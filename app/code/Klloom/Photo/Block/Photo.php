<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 01/07/18
 * Time: 18:39
 */

namespace Klloom\Photo\Block;

use ColorThief\ColorThief;
use ColorThief\Image\ImageLoader;

/**
 * Class Photo
 * @package Klloom\Photo\Block
 */
class Photo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Photo constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Klloom\Photo\Helper\Data $helperData
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Klloom\Photo\Helper\Data $helperData,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeManager             = $storeManager;
        $this->_registry                 = $registry;
        $this->_helperData               = $helperData;
        parent::__construct($context, $data);
        $this->directoryList = $directoryList;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaBaseUrl()
    {
        $storeId = $this->_storeManager->getStore();
        return $storeId->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return string
     * TODO Need to return the EXIF Meta Data
     */
    public function getCameraInformation()
    {
        return 'Canon EOS 5D Mark III Sigma 35.0 mm F/5.6 1/50s ISO 320';
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    function getPhotoInformation()
    {
        $data = array(
            'pixels' => '',
            'size'   => '',
            'aspect' => '',
            'cm'     => '',
            'mm'     => '',
            'in'     => '',
            'dpi'    => ''
        );

        if (!$this->getPhotoSrc()) {
            return $data;
        }

        $loader = new ImageLoader();
        $image  = $loader->load($this->getPhotoSrc());

        /**
         * DPI
         */
        $dpi = $this->_helperData->get_dpi($this->getPhotoSrc());

        /**
         * Image dimension
         */
        $width  = $image->getWidth();
        $height = $image->getHeight();

        /**
         * Image Ratio
         */
        $ratio = $this->_helperData->gcd($width, $height);

        /**
         * Basicamente, divide-se cada uma das dimensões (a horizontal e a vertical) por 300, obtendo-se a dimensão impressa em polegadas.
         * Depois converte-se de polegadas para centimetro.
         */

        /**
         * Inches dimension
         */
        $widthInch  = round(floatval($width / 300));
        $heightInch = round(floatval($height / 300));

        /**
         * Centimeter dimension
         */
        $widthCm  = round(floatval($widthInch / 0.39370079));
        $heightCm = round(floatval($heightInch / 0.39370079));

        /**
         * Milimeters dimension
         */
        $widthMm  = round(floatval($widthInch *  25.4), 0);
        $heightMm = round(floatval($heightInch * 25.4), 0);

        /**
         * Get image size
         */
        $size = $this->_helperData->fileSizeConvert(filesize($this->getPhotoSrc()));


        $data = array(
            'pixels' => $width . ' x ' . $height,
            'size'   => $size,
            'aspect' => ($width / $ratio) . ':' . ($height / $ratio),
            'cm'     => $widthCm . ' x ' . $heightCm,
            'mm'     => $widthMm . ' x ' . $heightMm,
            'in'     => $widthInch . ' x ' . $heightInch,
            'dpi'    => 300
        );

        return $data;

    }

    /**
     * @return null|string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getPhotoSrc()
    {
        $link_file = $this->getProductCollection();
        $out       = false;
        if ($link_file) {
            $mediaPath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) . '/downloadable/files/links/' . $link_file['link_file'];
            if (file_exists($mediaPath)) {
                $out = $mediaPath;
            }
        }
        return $out;
    }

    /**
     * @return bool
     * TODO Refactor this function to works correctly.
     */
    public function getProductCollection()
    {
        $product       = $this->getCurrentProduct()->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource      = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection    = $resource->getConnection();
        $tableName     = $resource->getTableName('downloadable_link');
        $sql           = "SELECT link_id, link_file FROM " . $tableName . " WHERE product_id = " . $product;
        $result        = $connection->fetchAll($sql);
        $link_file     = false;
        if (count($result)) {
            $link_file = $result[0];
        }
        return $link_file;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getPhotoColors()
    {
        $hexa    = [];
        if ($this->getPhotoSrc()) {
            $palette = ColorThief::getPalette($this->getPhotoSrc(), 5);
            foreach ($palette as $color) {
                $hexa[] = 'rgb(' . implode(', ', $color) . ')';
            }
        }
        return $hexa;
    }

    function getPixelsImageByProductId($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource      = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection    = $resource->getConnection();
        $tableName     = $resource->getTableName('downloadable_link');
        $sql           = "SELECT link_file FROM " . $tableName . " WHERE product_id = " . $productId;
        $result        = $connection->fetchAll($sql);
        if ($result) {
            $link_file = '';
            foreach ($result as $link) {
                $link_file = $link['link_file'];
            }
            $mediaPath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA) . '/downloadable/files/links/' . $link_file;
            $loader    = new ImageLoader();
            $image     = $loader->load($mediaPath);
            $width     = $image->getWidth();
            $height    = $image->getHeight();
            return $width . ' x ' . $height;
        }

    }

}