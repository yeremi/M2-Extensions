<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 20/09/18
 * Time: 19:26
 */

namespace Klloom\Trending\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Custom implements ObserverInterface
{

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {

        $resource   = $this->resourceConnection;
        $connection = $resource->getConnection();
        $tableName  = $this->getTableName();

        /**
         * $array = array (
         * [customer_id] => 1
         * [product_id] => 15
         * [action_comment] => (some ID)
         * [method] => save|delete
         * )
         */
        $data   = $observer->getData('klloom_event');
        $method = $data['method'];
        unset($data['method']);

        if ($method == 'insert') {
            $columns = implode(',', array_keys($data));
            $values  = implode(',', array_values($data));
            $sql     = "INSERT INTO " . $tableName . " (" . $columns . ") VALUES (" . $values . ")";
            $connection->query($sql);
        }

        if ($method == 'delete') {
            if (array_key_exists("action_comment", $data)) {
                $sql = "DELETE FROM " . $tableName . " WHERE md5(action_comment) = '" . $data['action_comment'] . "' AND 
                md5(product_id) = '" . $data['product_id'] . "' AND 
                customer_id =  " . $data['customer_id'];
                $connection->query($sql);
            }
            if (array_key_exists("action_like", $data)) {
                $sql = "DELETE FROM " . $tableName . " WHERE action_like = '" . $data['action_like'] . "' AND 
                product_id = '" . $data['product_id'] . "' AND 
                customer_id =  " . $data['customer_id'];
                $connection->query($sql);
            }
        }
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->resourceConnection->getTableName('klloom_trending');
    }
}