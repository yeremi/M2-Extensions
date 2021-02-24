<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 18/07/18
 * Time: 09:47
 */

namespace Klloom\Photo\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\DB\Ddl\Table;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * UpgradeData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '0.0.2') < 0) {
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'photo_color',
                [
                    'group'                   => 'General',
                    'type'                    => Table::TYPE_TEXT,
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Photo Colors',
                    'input'                   => 'text',
                    'class'                   => '',
                    'source'                  => '',
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => '',
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false
                ]
            );

            /**
             * https://en.wikipedia.org/wiki/Exif
             * Ex: Canon EOS 5D Mark III Sigma 35.0 mm F/5.6 1/50s ISO 320
             * TODO In this moment we will save all Exif information as json string in this field. Later we will analyze how and what information we sill show in frontpage.
             */
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'photo_exif_data',
                [
                    'group'                   => 'General',
                    'type'                    => Table::TYPE_TEXT,
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Photo Exif Information',
                    'input'                   => 'textarea',
                    'class'                   => '',
                    'source'                  => '',
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => '',
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false
                ]
            );

            /**
             * Photo Dimensions
             * Ex: 5760 x 3840 pixels 16.5 MB Aspect Ratio: 3:2
             */
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'photo_dimension',
                [
                    'group'                   => 'General',
                    'type'                    => Table::TYPE_TEXT,
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Photo Dimensions',
                    'input'                   => 'text',
                    'class'                   => '',
                    'source'                  => '',
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => '',
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false
                ]
            );
        }

        if (version_compare($context->getVersion(), '0.0.3') < 0) {
            /**
             * Photo Dimensions
             * Ex: 5760 x 3840 pixels 16.5 MB Aspect Ratio: 3:2
             */
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'photo_size',
                [
                    'group'                   => 'General',
                    'type'                    => Table::TYPE_TEXT,
                    'backend'                 => '',
                    'frontend'                => '',
                    'label'                   => 'Photo Size',
                    'input'                   => 'text',
                    'class'                   => '',
                    'source'                  => '',
                    'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'                 => true,
                    'required'                => false,
                    'user_defined'            => false,
                    'default'                 => '',
                    'searchable'              => false,
                    'filterable'              => false,
                    'comparable'              => false,
                    'visible_on_front'        => false,
                    'used_in_product_listing' => true,
                    'unique'                  => false
                ]
            );
        }
    }
}