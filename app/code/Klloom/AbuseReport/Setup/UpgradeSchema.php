<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/06/18
 * Time: 7:57 AM
 */

namespace Klloom\AbuseReport\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var TYPE_NAME $setup */

        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $table = $setup->getConnection()
                ->newTable($setup->getTable('klloom_reporting_status')

                )->addColumn(
                    'status_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'The Status ID'

                )->addColumn(
                    'status_code',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'The Status Name'

                )->setComment(
                    'Abuse Report status list'
                );

            $setup->getConnection()->createTable($table);

        }
        /**
         * add new column status_id in klloom_reporting_abuse
          */
        if(version_compare( $context->getVersion(),'1.0.2','<'))
        {
            $setup->getConnection()->addColumn(
                $setup->getTable('klloom_reporting_abuse'),
                'stop_by_abuse',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'default' => 0 ,
                    'comment' => 'Stop By Abuse',
                    'after' => 'report'
                ]
            );

            $setup->getConnection()->addIndex(
                $setup->getTable('klloom_reporting_abuse'),
                $setup->getIdxName('klloom_reporting_abuse', ['stop_by_abuse']),
                ['stop_by_abuse']
            );
            $setup->getConnection()->addIndex(
                $setup->getTable('klloom_reporting_abuse'),
                $setup->getIdxName('klloom_reporting_abuse', ['customer_id']),
                ['customer_id']
            );
            $setup->getConnection()->addIndex(
                $setup->getTable('klloom_reporting_abuse'),
                $setup->getIdxName('klloom_reporting_abuse', ['product_id']),
                ['product_id']
            );
        }

        $setup->endSetup();

    }
}