<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 6/27/18
 * Time: 3:21 PM
 */

namespace Klloom\ProductComments\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class Uninstall
 * @package Klloom\ProductComments\Setup
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
        $connection->dropTable('klloom_comments');
        $setup->endSetup();
    }
}