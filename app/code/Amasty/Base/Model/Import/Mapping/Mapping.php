<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Mapping;

/**
 * Class Mapping
 * @package Amasty\Base\Model\Import\Mapping
 */
class Mapping implements MappingInterface
{
    /**
     * @var string
     */
    protected $masterAttributeCode = '';

    /**
     * FYI: column names pattern [a-z][a-z0-9_]*
     * @var array
     */
    protected $mappings = [
        /**
         * csv_column_name => model_column_name
         * model_column_name (numeric key means model_column_name => model_column_name)
        **/
    ];

    /**
     * @var array
     */
    private $processedMapping;

    /**
     * @inheritdoc
     */
    public function getValidColumnNames()
    {
        return array_keys($this->processedMapping());
    }

    /**
     * @inheritdoc
     */
    public function getMappedField($columnName)
    {
        if (!isset($this->processedMapping()[$columnName])) {
            throw new \Amasty\Base\Exceptions\MappingColumnDoesntExist();
        }

        return $this->processedMapping()[$columnName];
    }

    /**
     * @inheritdoc
     */
    public function getMasterAttributeCode()
    {
        if (empty($this->masterAttributeCode)) {
            throw new \Amasty\Base\Exceptions\MasterAttributeCodeDoesntSet();
        }

        return $this->masterAttributeCode;
    }

    public function processedMapping()
    {
        if (null === $this->processedMapping) {
            $this->processedMapping = [];
            foreach ($this->mappings as $csvField => $field) {
                if (is_numeric($csvField)) {
                    $this->processedMapping[$field] = $field;
                } else {
                    $this->processedMapping[$csvField] = $field;
                }
            }
        }

        return $this->processedMapping;
    }
}
