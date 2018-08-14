<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Setup;

use Magento\Framework\DB\AggregatedFieldDataConverter;

class SerializedFieldDataConverter
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $connectionResource;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $connectionResource
    ) {
        $this->objectManager = $objectManager;
        $this->connectionResource = $connectionResource;
    }

    /**
     * Convert metadata from serialized to JSON format:
     *
     * @param $tableName
     * @param $identifierField
     * @param $fields
     * @return void
     */
    public function convertSerializedDataToJson($tableName, $identifierField, $fields)
    {
        /** @var AggregatedFieldDataConverter $aggregatedFieldConverter */
        $fieldConverter = $this->objectManager->get(AggregatedFieldDataConverter::class);
        $convertData = [];

        if (is_array($fields)) {
            foreach ($fields as $field) {
                $convertData[] = $this->getConvertedData($tableName, $identifierField, $field);
            }
        } else {
            $convertData[] = $this->getConvertedData($tableName, $identifierField, $fields);
        }

        $fieldConverter->convert(
            $convertData,
            $this->connectionResource->getConnection()
        );
    }

    /**
     * @param $tableName
     * @param $identifierField
     * @param $field
     * @return \Magento\Framework\DB\FieldToConvert
     */
    protected function getConvertedData($tableName, $identifierField, $field)
    {
        $instance = new \Magento\Framework\DB\FieldToConvert(
            \Magento\Framework\DB\DataConverter\SerializedToJson::class,
            $this->connectionResource->getTableName($tableName),
            $identifierField,
            $field
        );

        return $instance;
    }
}
