<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/27/18
 * Time: 4:17 PM
 */

namespace Klloom\ProductComments\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 2;
    const STATUS_NOT_APPROVED = 3;

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer            = $setup;
        $table_comment_status = $installer->getTable('klloom_comments_status');
        $commentStatuses      = [
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_NOT_APPROVED => 'Not Approved',
        ];
        $installer->getConnection()->truncateTable($table_comment_status);
        foreach ($commentStatuses as $k => $v) {
            $bind = ['status_id' => $k, 'status_code' => $v];
            $installer->getConnection()->insertForce($table_comment_status, $bind);
        }
    }
}