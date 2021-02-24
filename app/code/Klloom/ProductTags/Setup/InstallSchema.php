<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 1/31/19
 * Time: 19:40 PM
 */

namespace Klloom\ProductTags\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Zend_Db_Exception;

/**
 * Class InstallSchema
 * @package Klloom\ProductTags\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var TYPE_NAME $setup */

        $setup->startSetup();

        /**
         * Hidden
         */
        $table = $setup->getConnection()->newTable($setup->getTable('klloom_crosstags_hidden'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'

            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Photo Id'

            )->addColumn(
                'profile_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Seller Profile Id'

            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Created At'

            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'

            )->setComment(
                'List of hidden photos marked with hashtags'
            );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}