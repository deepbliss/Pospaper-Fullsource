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
 * @author       MageModule admin@magemodule.com
 * @copyright   2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Model\Data;

use Magento\Framework\Exception\LocalizedException;

class Formatter implements FormatterInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $objectFactory;

    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $helper;

    /**
     * This mapper is only for internal use. Should not have customized fields from users
     *
     * @var \MageModule\Core\Model\Data\Mapper
     */
    private $systemFieldMapper;

    /**
     * Field mapper that contains field mappings created by end user
     *
     * @var \MageModule\Core\Model\Data\Mapper
     */
    private $customFieldMapper;

    /**
     * @var \MageModule\Core\Model\Data\Formatter\Iterator[]
     */
    private $iterators = [];

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $glue;

    /**
     * @var string|null
     */
    private $prepend;

    /**
     * @var string|null
     */
    private $append;

    /**
     * @var string
     */
    private $valueWrapPattern;

    /**
     * @var array
     */
    private $includedFields;

    /**
     * @var array
     */
    private $excludedFields;

    /**
     * @var array
     */
    private $defaultValues;

    /**
     * @var bool
     */
    private $allowNewlineChar;

    /**
     * @var bool
     */
    private $allowReturnChar;

    /**
     * @var bool
     */
    private $allowTabChar;

    /**
     * Formatter constructor.
     *
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \MageModule\Core\Helper\Data         $helper
     * @param \MageModule\Core\Model\Data\Mapper   $systemFieldMapper
     * @param \MageModule\Core\Model\Data\Mapper   $customFieldMapper
     * @param array                                $iterators
     * @param string                               $format
     * @param string|array|null                    $glue
     * @param string|array|null                    $prepend
     * @param string|array|null                    $append
     * @param string|null                          $valueWrapPattern
     * @param array                                $includedFields
     * @param array                                $excludedFields
     * @param array                                $defaultValues
     * @param bool                                 $allowNewlineChar
     * @param bool                                 $allowReturnChar
     * @param bool                                 $allowTabChar
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory,
        \MageModule\Core\Helper\Data $helper,
        \MageModule\Core\Model\Data\Mapper $systemFieldMapper,
        \MageModule\Core\Model\Data\Mapper $customFieldMapper,
        array $iterators = [],
        $format = 'string',
        $glue = null,
        $prepend = null,
        $append = null,
        $valueWrapPattern = null,
        array $includedFields = [],
        array $excludedFields = [],
        array $defaultValues = [],
        $allowNewlineChar = true,
        $allowReturnChar = true,
        $allowTabChar = true
    ) {
        $this->objectFactory     = $objectFactory;
        $this->helper            = $helper;
        $this->systemFieldMapper = $systemFieldMapper;
        $this->setAllowNewlineChar($allowNewlineChar);
        $this->setAllowReturnChar($allowReturnChar);
        $this->setAllowTabChar($allowTabChar);
        $this->setCustomFieldMapper($customFieldMapper);
        $this->setIterators($iterators);
        $this->setFormat($format);
        $this->setGlue($glue);
        $this->setPrepend($prepend);
        $this->setAppend($append);
        $this->setValueWrapPattern($valueWrapPattern);
        $this->setIncludedFields($includedFields);
        $this->setExcludedFields($excludedFields);
        $this->setDefaultValues($defaultValues);
    }

    /**
     * @return \MageModule\Core\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return null|\MageModule\Core\Model\Data\Mapper
     */
    public function getSystemFieldMapper()
    {
        return $this->systemFieldMapper;
    }

    /**
     * @param \MageModule\Core\Model\Data\Mapper $mapper
     *
     * @return $this
     */
    public function setCustomFieldMapper(\MageModule\Core\Model\Data\Mapper $mapper = null)
    {
        $this->customFieldMapper = $mapper;

        return $this;
    }

    /**
     * @return null|\MageModule\Core\Model\Data\Mapper
     */
    public function getCustomFieldMapper()
    {
        return $this->customFieldMapper;
    }

    /**
     * @param \MageModule\Core\Model\Data\Formatter\Iterator[] $iterators
     *
     * @return $this
     */
    public function setIterators(array $iterators)
    {
        foreach ($iterators as $field => $iterator) {
            $this->addIterator($field, $iterator);
        }

        return $this;
    }

    /**
     * @param string                                         $field
     * @param \MageModule\Core\Model\Data\Formatter\Iterator $iterator
     *
     * @return $this
     */
    public function addIterator($field, \MageModule\Core\Model\Data\Formatter\Iterator $iterator)
    {
        $this->iterators[$field] = $iterator;

        return $this;
    }

    /**
     * @return \MageModule\Core\Model\Data\Formatter\Iterator[]
     */
    public function getIterators()
    {
        return $this->iterators;
    }

    /**
     * @param string $field
     *
     * @return \MageModule\Core\Model\Data\Formatter\Iterator|null
     */
    public function getIterator($field)
    {
        if (isset($this->iterators[$field])) {
            return $this->iterators[$field];
        }

        return null;
    }

    /**
     * @param array|\Magento\Framework\DataObject $item
     */
    private function executeIterators(&$item)
    {
        foreach ($this->getIterators() as $field => $iterator) {
            if (is_array($item) && isset($item[$field])) {
                $item[$field] = $iterator->iterate($item[$field]);
            } elseif ($item instanceof \Magento\Framework\DataObject) {
                $item->setData($field, $iterator->iterate($item->getData($field)));
            }
        }
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setAllowNewlineChar($bool)
    {
        $this->allowNewlineChar = (bool)$bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowNewlineChar()
    {
        return $this->allowNewlineChar;
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setAllowReturnChar($bool)
    {
        $this->allowReturnChar = (bool)$bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowReturnChar()
    {
        return $this->allowReturnChar;
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setAllowTabChar($bool)
    {
        $this->allowTabChar = (bool)$bool;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowTabChar()
    {
        return $this->allowTabChar;
    }

    /**
     * @param string|array $value
     *
     * @return $this
     */
    public function setGlue($value)
    {
        $this->glue = $this->prepareGlue($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getGlue()
    {
        return $this->glue;
    }

    /**
     * @param string|array|null $glue
     *
     * @return string
     */
    public function prepareGlue($glue)
    {
        if (is_object($glue)) {
            return null;
        }

        if (is_array($glue)) {
            foreach ($glue as &$subglue) {
                $subglue = $this->prepareGlue($subglue);
            }
            $glue = implode('', $glue);
        }

        if ($glue === '\n' || $glue === 'newline') {
            $glue = $this->getAllowNewlineChar() ? PHP_EOL : null;
        }

        if ($glue === '\r' || $glue === 'return') {
            $glue = $this->getAllowReturnChar() ? "\r" : null;
        }

        if ($glue === '\t' || $glue === 'tab') {
            $glue = $this->getAllowTabChar() ? "\t" : null;
        }

        return $glue;
    }

    /**
     * Set any value or character that should be place at start of formatted string
     *
     * @param null|string|array $value
     *
     * @return $this
     */
    public function setPrepend($value)
    {
        $this->prepend = $this->prepareGlue($value);

        return $this;
    }

    /**
     * Get value or character that should be place at start of formatted string
     *
     * @return null|string
     */
    public function getPrepend()
    {
        return $this->prepend;
    }

    /**
     * @param string|null $value
     * @param string|null $prepend
     *
     * @return string
     */
    public function prepend($value, $prepend = null)
    {
        if ($prepend === null) {
            $prepend = $this->getPrepend();
        }

        return $prepend . $value;
    }

    /**
     * Set any value or character that should be place at end of formatted string
     *
     * @param null|string|array $value
     *
     * @return $this
     */
    public function setAppend($value)
    {
        $this->append = $this->prepareGlue($value);

        return $this;
    }

    /**
     * Get value or character that should be place at end of formatted string
     *
     * @return null|string
     */
    public function getAppend()
    {
        return $this->append;
    }

    /**
     * @param string|null $value
     * @param string|null $append
     *
     * @return string
     */
    public function append($value, $append = null)
    {
        if ($append === null) {
            $append = $this->getAppend();
        }

        return $value . $append;
    }

    /**
     * @param string|null $pattern
     *
     * @return $this
     */
    public function setValueWrapPattern($pattern)
    {
        $this->valueWrapPattern = $pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getValueWrapPattern()
    {
        return $this->valueWrapPattern;
    }

    /**
     * @param null|string $field
     * @param null|string $value
     * @param null|string $pattern
     *
     * @return string
     */
    public function convertPlaceholderValues($field = null, $value = null, $pattern = null)
    {
        $pairs['{{FIELD}}']   = strtoupper($field);
        $pairs['{{field}}']   = strtolower($field);
        $pairs['{{value}}']   = $value;
        $pairs['{{newline}}'] = $this->getAllowNewlineChar() ? PHP_EOL : null;
        $pairs['{{return}}']  = $this->getAllowReturnChar() ? "\r" : null;
        $pairs['{{tab}}']     = $this->getAllowTabChar() ? "\t" : null;

        return str_replace(array_keys($pairs), $pairs, $pattern);
    }

    /**
     * @param string|null $field
     * @param string|null $value
     * @param string|null $pattern
     *
     * @return string
     */
    public function wrapValue($field, $value, $pattern = null)
    {
        if ($pattern === null) {
            $pattern = $this->getValueWrapPattern();
        }

        return $this->convertPlaceholderValues($field, $value, $pattern);
    }

    /**
     * If included fields empty, all fields will be included
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setIncludedFields(array $fields)
    {
        $this->includedFields = $fields;

        return $this;
    }

    /**
     * If included fields empty, all fields will be included
     *
     * @return array
     */
    public function getIncludedFields()
    {
        return $this->includedFields;
    }

    /**
     * @param array $array
     */
    public function filterArrayByIncludedFields(array &$array)
    {
        if ($this->getIncludedFields()) {
            $newArray = [];
            foreach ($this->getIncludedFields() as $field => $null) {
                $newArray[$field] = isset($array[$field]) ? $array[$field] : null;
            }

            $array = $newArray;
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    public function filterObjectByIncludedFields(\Magento\Framework\DataObject &$object)
    {
        $data = $object->getData();
        $this->filterArrayByIncludedFields($data);
        $object->setData($data);
    }

    /**
     * @param array|\Magento\Framework\DataObject $item
     *
     * @return array|\Magento\Framework\DataObject
     */
    public function filterByIncludedFields(&$item)
    {
        if ($this->getIncludedFields()) {
            is_array($item) ?
                $this->filterArrayByIncludedFields($item) :
                $this->filterObjectByIncludedFields($item);
        }

        return $item;
    }

    /**
     * If included fields empty, all fields will be included
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setExcludedFields(array $fields)
    {
        $this->excludedFields = $fields;

        return $this;
    }

    /**
     * If included fields empty, all fields will be included
     *
     * @return array
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }

    /**
     * @param array $array
     */
    public function filterArrayByExcludedFields(array &$array)
    {
        $this->helper->removeElements($array, $this->getExcludedFields());
    }

    /**
     * @param \Magento\Framework\DataObject $object
     */
    public function filterObjectByExcludedFields(\Magento\Framework\DataObject &$object)
    {
        $data = $object->getData();
        $this->filterArrayByExcludedFields($data);
        $object->setData($data);
    }

    /**
     * @param array|\Magento\Framework\DataObject $item
     *
     * @return array|\Magento\Framework\DataObject
     */
    public function filterByExcludedFields(&$item)
    {
        if ($this->getExcludedFields()) {
            is_array($item) ?
                $this->filterArrayByExcludedFields($item) :
                $this->filterObjectByExcludedFields($item);
        }

        return $item;
    }

    /**
     * Array should be structure $field => $defaultValue
     *
     * @param array $values
     *
     * @return $this
     */
    public function setDefaultValues(array $values)
    {
        $this->defaultValues = $values;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

    /**
     * @param $item
     */
    public function applyDefaultValues(&$item)
    {
        if (is_array($item)) {
            foreach ($this->getDefaultValues() as $field => $value) {
                if (array_key_exists($field, $item)) {
                    if ($item[$field] === null || trim($item[$field]) === '') {
                        $item[$field] = $value;
                    }
                }
            }
        } elseif ($item instanceof \Magento\Framework\DataObject) {
            foreach ($this->getDefaultValues() as $field => $value) {
                if ($item->hasData($field) && ($item->getData($field) === null || trim($item->getData($field)) === '')) {
                    $item->setData($field, $value);
                }
            }
        }
    }

    /**
     * @param string $format
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setFormat($format)
    {
        $formats = ['string', 'array', 'object'];
        if (!in_array($format, $formats)) {
            throw new LocalizedException(
                __('%1 is not a valid format. Acceptable values are %2.', $format, implode(', ', $formats))
            );
        }
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param array|\Magento\Framework\DataObject $item
     *
     * @return string|array|\Magento\Framework\DataObject
     */
    public function format($item)
    {
        if ($item === null || $item === false) {
            return null;
        }

        $mapper = $this->getSystemFieldMapper();
        if ($mapper) {
            $item = $mapper->map($item);
        }

        $this->filterByIncludedFields($item);
        $this->filterByExcludedFields($item);
        $this->applyDefaultValues($item);

        if (is_array($item)) {
            $item = $this->objectFactory->create(['data' => $item]);
        }

        $this->executeIterators($item);

        $array = $item->getData();

        $this->helper->removeObjects($array);

        /** remove subarrays and subobjects */
        foreach ($array as $field => &$value) {
            if (is_array($value)) {
                $this->helper->removeObjects($value);
                $this->helper->removeArrays($value);
                $value = is_array($value) ? implode($this->getGlue(), $value) : $value;
            }

            if ($this->getValueWrapPattern()) {
                $value = $this->wrapValue($field, $value, $this->getValueWrapPattern());
            }
        }

        $mapper = $this->getCustomFieldMapper();
        if ($mapper) {
            $array = $this->getCustomFieldMapper()->map($array);
        }

        switch ($this->getFormat()) {
            case 'array':
                $result = $array;
                break;
            case 'object':
                $result = $this->objectFactory->create(['data' => $array]);
                break;
            default:
                $result = $this->append(
                    $this->getAppend(),
                    $this->prepend(
                        implode($this->getGlue(), $array),
                        $this->getPrepend()
                    )
                );
        }

        return $result;
    }
}
