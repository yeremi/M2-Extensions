<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/25/18
 * Time: 7:35 PM
 */

namespace Klloom\AbuseReport\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class Uninstall
 * @package Klloom\AbuseReport\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        $connection->dropTable('klloom_reporting_abuse');
        $setup->endSetup();
    }
}