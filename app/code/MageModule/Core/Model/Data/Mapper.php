<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule admin@magemodule.com
 * @copyright     2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Model\Data;

class Mapper
{
    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $helper;

    /**
     * @var array
     */
    private $mapping = [];

    /**
     * Mapper constructor - can extend other mappings passed in through $extendMappings
     *
     * @param \MageModule\Core\Helper\Data $helper
     * @param array                        $extendMappings
     * @param array                        $mapping
     */
    public function __construct(
        \MageModule\Core\Helper\Data $helper,
        array $extendMappings = [],
        array $mapping = []
    ) {
        $this->helper = $helper;
        $class        = self::class;
        foreach ($extendMappings as $mapper) {
            if ($mapper instanceof $class) {
                foreach ($mapper->getMapping() as $oldField => $newField) {
                    $this->addMapping($oldField, $newField);
                }
            }
        }

        foreach ($mapping as $oldField => $newField) {
            $this->addMapping($oldField, $newField);
        }
    }

    /**
     * @param array $mapping
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateMapping(array $mapping)
    {
        foreach ($mapping as $field) {
            if (!is_string($field)) {
                //      throw new LocalizedException(__('Mapped field names must be a string.'));
            }
        }

        return true;
    }

    /**
     * Must be an array of $oldField => $newField
     * Only map fields that do not already have the correct name
     *
     * @param array $mapping
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setMapping(array $mapping)
    {
        $this->validateMapping($mapping);
        $this->mapping = $mapping;

        return $this;
    }

    /**
     * Returns array of $oldField => $newField
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param string $oldField
     *
     * @return string|null
     */
    public function getMappedField($oldField)
    {
        if ($this->isMapped($oldField)) {
            return $this->mapping[$oldField];
        }

        return null;
    }

    /**
     * @param string $oldField
     * @param string $newField
     *
     * @return $this
     */
    public function addMapping($oldField, $newField)
    {
        $this->mapping[$oldField] = $newField;
        $this->validateMapping($this->mapping);

        return $this;
    }

    /**
     * @param string $oldField
     *
     * @return $this
     */
    public function removeMapping($oldField)
    {
        if ($this->isMapped($oldField)) {
            unset($this->mapping[$oldField]);
        }

        return $this;
    }

    /**
     * @param string $oldField
     *
     * @return bool
     */
    public function isMapped($oldField)
    {
        return array_key_exists($oldField, $this->mapping);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @param bool                          $keepOrigFields
     *
     * @return \Magento\Framework\DataObject
     */
    public function mapObject(\Magento\Framework\DataObject &$object, $keepOrigFields = false)
    {
        /**
         * the purpose of this exercise is to be able to map data from subarrays but still keep the array
         * in the same order when accessing subarray data with
         * $object->getData('product_options/info_buyRequest/product') or something similar
         */
        $validKeys = array_merge(
            array_keys($object->getData()),
            array_values($this->getMapping())
        );

        $allData = [];
        $paths   = $this->helper->stringifyPaths($object->getData());
        foreach ($paths as $key) {
            $allData[$key] = $object->getData($key);
        }

        $mappedData = $this->mapArray($allData, $keepOrigFields);
        foreach ($mappedData as $key => &$value) {
            if (!in_array($key, $validKeys)) {
                unset($mappedData[$key]);
            }
        }

        return $object->setData($mappedData);
    }

    /**
     * @param array $array
     * @param bool  $keepOrigFields
     *
     * @return array
     */
    public function mapArray(array &$array, $keepOrigFields = false)
    {
        $mappedData = [];
        foreach ($array as $oldField => $value) {
            $newField = $this->getMappedField($oldField);
            $field    = $newField === null ? $oldField : $newField;
            if ($keepOrigFields) {
                $mappedData[$oldField] = $value;
            }

            if (is_array($field)) {
                foreach ($field as $subfield) {
                    $mappedData[$subfield] = $value;
                }
            } else {
                $mappedData[$field] = $value;
            }
        }

        return $array = $mappedData;
    }

    /**
     * @param array|\Magento\Framework\DataObject $data
     * @param bool                                $keepOrigFields
     *
     * @return array|\Magento\Framework\DataObject
     */
    public function map(&$data, $keepOrigFields = false)
    {
        if (is_array($data)) {
            $data = $this->mapArray($data, $keepOrigFields);
        } elseif ($data instanceof \Magento\Framework\DataObject) {
            $data = $this->mapObject($data, $keepOrigFields);
        }

        return $data;
    }
}
