<?php
/**
 * User: Amit Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Klloom\AbuseReport\Model\ResourceModel\Post\Grid;


class Collection extends \Klloom\AbuseReport\Model\ResourceModel\Post\Collection
{


    protected function _initSelect()
    {
        parent::_initSelect();
        $this->showCustomerInfo(true)->addProductName(true);
        return $this;
    }
    /**
     * Adds customer info to select
     *
     * @return $this
     */
    public function showCustomerInfo()
    {
        $this->getSelect()->joinLeft(
            [
                'customer' => $this->getTable('customer_entity')
            ],
            'main_table.customer_id = customer.entity_id',
            ['firstname', 'lastname']
        )->joinLeft(
            [
                'product' => $this->getTable('catalog_product_entity')
            ],
            'main_table.product_id = product.entity_id',
            ['sku']
        );

        return $this;
    }
    public function addProductName()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $nameAttribute = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product')->getAttribute('name');
        $this->getSelect()->joinLeft(
            array('cpev' => $nameAttribute->getBackend()->getTable()),
            'cpev.entity_id=main_table.entity_id AND cpev.attribute_id='.$nameAttribute->getAttributeId().'
             AND  cpev.store_id = 0',
            array('productname' => 'value')
        );
        return $this;
    }
    /**
     * Returns select count sql
     *
     * @return string
     */
    public function getSelectCountSql()
    {
        $select = parent::getSelectCountSql();
        $countSelect = clone $this->getSelect();

        $countSelect->reset(\Magento\Framework\DB\Select::HAVING);

        return $select;
    }
}