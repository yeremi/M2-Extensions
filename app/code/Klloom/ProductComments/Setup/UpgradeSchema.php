<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 18/09/18
 * Time: 23:23
 */

namespace Klloom\ProductComments\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $tableName = $setup->getTable('klloom_comments');
            if ($setup->getConnection()->isTableExists($tableName) == true) {

                $connection = $setup->getConnection();

                $connection->dropColumn($tableName, 'updated_at');

                $connection->modifyColumn($tableName, 'created_at',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        'nullable' => false,
                        'default'  => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                        'comment'  => 'Created At'
                    ]
                );

            }
        }

        $setup->endSetup();
    }
}