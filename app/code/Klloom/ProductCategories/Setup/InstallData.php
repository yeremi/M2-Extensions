<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 03/08/18
 * Time: 10:27
 */

namespace Klloom\ProductCategories\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $productCategories = array(
            'Abstract',
            'Aerial',
            'Animals',
            'Architecture',
            'Art',
            'Black and White',
            'Brand Lovers',
            'Brand Related',
            'Celebrity',
            'Concert',
            'Family',
            'Fashion',
            'Fashion Model',
            'Film',
            'Food',
            'Gourmet',
            'iPhoneography',
            'Journalism',
            'Landscape',
            'Life Style',
            'Lomography',
            'Macro and Close-Up',
            'Music',
            'Nature',
            'Newsworthy',
            'Night',
            'Nude',
            'People',
            'Portrait',
            'Social Cause',
            'Sport',
            'Still Life',
            'Travel',
            'Underwater',
            'Urban'
        );

        // TODO Create categories

    }
}