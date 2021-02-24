<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 02/07/18
 * Time: 15:47
 */

namespace Klloom\License\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

class Options extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $optionFactory;

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        /* your Attribute options list*/
        $this->_options = [
            ['label' => 'Select...', 'value' => 0],
            ['label' => 'Commercial Use', 'value' => 1],
            ['label' => 'Editorial Use Only', 'value' => 2]
        ];
        return $this->_options;
    }

    /**
     * @param int|string $value
     * @return bool|string
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default'  => null,
                'extra'    => null,
                'type'     => Table::TYPE_INTEGER,
                'nullable' => true,
                'note'  => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }
}