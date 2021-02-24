<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 12/19/18
 * Time: 12:41 PM
 */

namespace Klloom\Financial\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class InstallSchema
 * @package Klloom\Financial\Setup
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
            $setup->getTable('klloom_financial_report')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'

        )->addColumn(
            'order_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Order ID'

        )->addColumn(
            'seller_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Customer who sell the photo'

        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'

        )/*->addColumn(
            'buyer_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Customer who bought the photo'

        )*/
        ->addColumn(
            'product_invoiced',
            Table::TYPE_TEXT,
            255,
            [],
            'Total invoiced in base currency'
        )->addColumn(
            'exchange',
            Table::TYPE_TEXT,
            255,
            [],
            'save the current dollar exchange'
        )->addColumn(
            'brl_conversion',
            Table::TYPE_TEXT,
            255,
            [],
            'Prices on BRL'

        )->addColumn(
            'status',
            Table::TYPE_TEXT,
            255,
            [],
            'Pendente, pago, ...?'

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