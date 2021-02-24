<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 06/07/18
 * Time: 08:04
 */

namespace Klloom\AbuseReport\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        /**
         * Initial categories for report abuse in images
         */
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $STATUS = array(
                'Adult content (NSFW)',
                'Offensive (obscene, rude)',
                'Copyright issues',
                'Offtopic (trolling)',
                'Spam (ads)',
                'Wrong content (3D, illustration)',
                'Innacurate location'
            );

            foreach ($STATUS as $value) {
                $bind = ['status_code' => $value];
                $installer->getConnection()->insertForce($installer->getTable('klloom_reporting_status'), $bind);
            }
        }
    }
}