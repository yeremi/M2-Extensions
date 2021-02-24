<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/27/18
 * Time: 3:21 PM
 */

namespace Klloom\ProductComments\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class InstallSchema
 * @package Klloom\ProductComments\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var TYPE_NAME $setup */

        $setup->startSetup();

        /**
         * The Comments
         */
        $table = $setup->getConnection()->newTable($setup->getTable('klloom_comments'))
            ->addColumn(
                'comment_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'The ID'

            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Customer Id'

            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Product Id'

            )->addColumn(
                'comment',
                Table::TYPE_TEXT,
                255,
                [],
                'The Comment'

            )->addColumn(
                'child_of',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Child of this entity'

            )->addColumn(
                'status_id',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => 2],
                'Status flag'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'

            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'

            )->setComment(
                'Abuse reports of photos'
            );

        $setup->getConnection()->createTable($table);

        /**
         * Comment Status
         */
        $table = $setup->getConnection()->newTable($setup->getTable('klloom_comments_status'))
            ->addColumn(
                'status_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'The ID'

            )->addColumn(
                'status_code',
                Table::TYPE_TEXT,
                255,
                [],
                'The Comment'

            )->setComment(
                'Abuse reports of photos'
            );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}