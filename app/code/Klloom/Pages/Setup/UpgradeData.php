<?php

namespace Klloom\Pages\Setup;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * UpgradeData constructor.
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory $pageFactory
    )
    {
        $this->_pageFactory = $pageFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.2.3') < 0) {
            $this->LegalPages();
            $this->Home();
            $this->AllPhotos();
            $this->NewPhotos();
            $this->About();
            $this->CauseAndEffect();
            $this->HowItWorks();
            $this->Manifest();
            $this->Faq();
            $this->NoRoute();
        }
        $setup->endSetup();
    }

    private function NoRoute()
    {
        $page = $this->_pageFactory->create()->load('no-route', 'identifier');
        if ($page->getId()) {
            $page->setTitle("Oops, nothing to see here.")
                ->setContentHeading('Oops, nothing to see here.')
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\NoRoute"}}')
                ->setMetaTitle("Klloom, nothing to see here.")
                ->setMetaDescription("The page you requested was not found.")
                ->setPageLayout('1column')
                ->save();
        }
    }

    /**
     * @throws \Exception
     */
    private function Faq()
    {
        $home = $this->_pageFactory->create()->load('faq', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle("Klloom FAQ")
                ->setIdentifier('faq')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\Faq"}}')
                ->setMetaTitle("Klloom FAQ")
                ->setMetaDescription("Klloom FAQ")
                ->save();
        }    }

    /**
     *
     */
    private function CauseAndEffect()
    {
        $home = $this->_pageFactory->create()->load('cause-and-effect', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle("Cause&Effect")
                ->setIdentifier('cause-and-effect')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\CauseAndEffect"}}')
                ->setMetaTitle("Cause&Effect")
                ->setMetaDescription("Let’s be honest. Rethinking the world’s economy model is even more urgent than rethinking the stock photo market. We do believe in a world where corporations can monetize their business while making a difference for the future of our planet, far beyond all bullshit. Twelve percent of all Klloom’s photo licensing incomes goes to our donation partners, which makes Klloom the world's first tangible web initiative that carries a social compromise as an intrinsic part of its core business.")
                ->save();
        }
    }

    /**
     *
     */
    private function HowItWorks()
    {
        $home = $this->_pageFactory->create()->load('how-it-works', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle("How It Works")
                ->setIdentifier('how-it-works')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\HowItWorks"}}')
                ->setMetaTitle("How It Works")
                ->setMetaDescription("Klloom is a user-driven stock image bank based on sharing economy where you can buy and sell photos while helping a humanitarian cause. License your photographs directly to brands, agencies and photo lovers. Buy real life shots straight from the authors and use them in your campaigns. All for reasonable prices and under a simple license policy. Make extra money doing what you love and support an alternative stock photo market.")
                ->save();
        }
    }

    /**
     *
     */
    private function Manifest()
    {
        $home = $this->_pageFactory->create()->load('manifest', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle("Manifest")
                ->setIdentifier('manifest')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\Manifest"}}')
                ->setMetaTitle("")
                ->setMetaDescription("")
                ->save();
        }
    }

    /**
     * @throws \Exception
     */
    private function About()
    {
        $home = $this->_pageFactory->create()->load('about', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle("About Klloom: photo’s cause and effect")
                ->setIdentifier('about')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\About"}}')
                ->save();
        } else {
            $home->setTitle("About Klloom: photo’s cause and effect");
            $home->setContent('{{widget type="Klloom\Pages\Block\Widget\About"}}');
            $home->setMetaTitle("Klloom: photo’s cause and effect");
            $home->save();
        }
    }

    /**
     *
     */
    private function LegalPages()
    {
        $home = $this->_pageFactory->create()->load('behavior-guidelines', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('Behavior Guidelines')
                ->setIdentifier('behavior-guidelines')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\BehaviorGuidelines"}}')
                ->save();
        }

        $home = $this->_pageFactory->create()->load('contributor-agreement', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('Contributor Agreement')
                ->setIdentifier('contributor-agreement')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\ContributorAgreement"}}')
                ->save();
        }

        $home = $this->_pageFactory->create()->load('license-agreement', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('License Agreement')
                ->setIdentifier('license-agreement')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\LicenseAgreement"}}')
                ->save();
        }

        $home = $this->_pageFactory->create()->load('privacy-policy', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('Privacy Policy')
                ->setIdentifier('privacy-policy')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\PrivacyPolicy"}}')
                ->save();
        }
        $home = $this->_pageFactory->create()->load('terms-of-use', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('Terms of Use')
                ->setIdentifier('terms-of-use')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Klloom\Pages\Block\Widget\Terms"}}')
                ->save();
        }
    }

    /**
     * @throws \Exception
     */
    private function Home()
    {
        $home = $this->_pageFactory->create()->load('home', 'identifier');
        if ($home->getId()) {
            $home->setTitle("Klloom");
            $home->setContent("");
            $home->setMetaTitle("Klloom: photo’s cause and effect");
            $home->setMetaKeywords("photos, society, economy");
            $home->setMetaDescription("Klloom is a user-driven stock image bank based on sharing economy where you can buy and sell photos while helping a humanitarian cause. 
License your photographs directly to brands, agencies and photo lovers. 
Buy real life shots straight from the authors and use them in your campaigns. 
Make extra money doing what you love and support an alternative stock photo market. 
Klloom: photo’s cause and effect.");
            $home->save();
        }
    }

    /**
     * @throws \Exception
     */
    private function AllPhotos()
    {

        $home = $this->_pageFactory->create()->load('all-photos', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('All Photos')
                ->setIdentifier('all-photos')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Magento\Catalog\Block\Product\Widget\NewWidget" display_type="all_products" show_pager="0" products_count="1000" template="Magento_CatalogWidget::product/widget/content/all-photos.phtml"}}')
                ->save();
        } else {
            $home->setContent('{{widget type="Magento\Catalog\Block\Product\Widget\NewWidget" display_type="all_products" show_pager="0" products_count="1000" template="Magento_CatalogWidget::product/widget/content/all-photos.phtml"}}');
            $home->save();
        }
    }

    /**
     * @throws \Exception
     */
    private function NewPhotos()
    {
        $home = $this->_pageFactory->create()->load('new-photos', 'identifier');
        if (!$home->getId()) {
            $page = $this->_pageFactory->create();
            $page->setTitle('New')
                ->setIdentifier('new-photos')
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores(array(0))
                ->setContent('{{widget type="Magento\Framework\View\Element\Template" display_type="new_products" show_pager="0" products_count="1000" template="Magento_CatalogWidget::product/widget/content/new_grid.phtml" cache_lifetime="86400"}}')
                ->save();
        } else {
            $home->setContent('{{widget type="Magento\Framework\View\Element\Template" display_type="new_products" show_pager="0" products_count="1000" template="Magento_CatalogWidget::product/widget/content/new_grid.phtml" cache_lifetime="86400"}}');
            $home->save();
        }
    }
}