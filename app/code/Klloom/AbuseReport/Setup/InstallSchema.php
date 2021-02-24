<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/25/18
 * Time: 7:34 PM
 */

namespace Klloom\AbuseReport\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class InstallSchema
 * @package Klloom\AbuseReport\Setup
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

        $table = $setup->getConnection()->newTable(
            $setup->getTable('klloom_reporting_abuse')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity id'

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
            'report',
            Table::TYPE_TEXT,
            255,
            [],
            'Title of abuse subject of photo'

        )->addColumn(
            'url_key',
            Table::TYPE_TEXT,
            255,
            [],
            'Photo URL Key'

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
        $setup->endSetup();
    }
}