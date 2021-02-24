<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 04/07/18
 * Time: 11:12
 * https://magento.stackexchange.com/questions/93787/magento-2-create-customer-group-programatically/194937#194937
 */

namespace Klloom\CustomerGroups\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

//use Magento\Customer\Model\GroupFactory;

class InstallData implements InstallDataInterface
{

    protected $groupFactory;

    /**
     * InstallData constructor.
     * @param \Magento\Customer\Model\Group $groupFactory
     */
    public function __construct(
        \Magento\Customer\Model\Group $groupFactory
    )
    {

        $this->groupFactory = $groupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $usersCategories = array(
            'Photographer',
            'Collective of Photographers',
            'Collector/Photo Lover',
            'Celebrity',
            'Model',
            'Agency',
            'Brand',
            'Production Company',
            'Media Company/Publisher',
            'Organization',
        );

        //$installer = $setup;
        $setup->startSetup();
        foreach ($usersCategories as $k => $usersCategory) {
            /*$bind = [
                'customer_group_code' => $usersCategory,
                'tax_class_id'        => 3
            ];
            $installer->getConnection()->insertForce($installer->getTable('customer_group'), $bind);*/

            $setup->getConnection()->insertForce($setup->getTable('customer_group'),
                [
                    'customer_group_code' => $usersCategory,
                    'tax_class_id'        => 3
                ]
            );
        }
        $setup->endSetup();
    }
}