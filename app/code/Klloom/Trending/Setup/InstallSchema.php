<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 20/09/18
 * Time: 18:19
 */

namespace Klloom\Trending\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableTrending = 'klloom_trending';

        if (!$installer->tableExists($tableTrending)) {
            $table = $installer->getConnection()->newTable($installer->getTable($tableTrending))
                ->addColumn('entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Entity Id'
                )
                ->addColumn('customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'The customer who made some action'
                )
                ->addColumn('product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'The product actioned'
                )
                ->addColumn('action_comment',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was commented'
                )
                ->addColumn('action_like',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was liked'
                )
                ->addColumn('action_view',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was visited'
                )
                ->addColumn('action_friend',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was sent to a friend'
                )
                ->addColumn('action_share',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was shared to social network'
                )
                ->addColumn('action_buy',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was purchased'
                )
                ->addColumn('action_report',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'unsigned' => true,
                        'nullable' => true,
                        'default'  => '0'
                    ],
                    'If product was reported as abuse. This action will decrease points.'
                )
                ->addColumn('created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created at'
                )
                ->setComment('Klloom Trending Table');

            $installer->getConnection()->createTable($table);
            $installer->endSetup();
        }
    }
}