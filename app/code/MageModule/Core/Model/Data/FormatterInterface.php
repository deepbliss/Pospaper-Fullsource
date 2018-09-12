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

interface FormatterInterface
{
    /**
     * @return \MageModule\Core\Helper\Data
     */
    public function getHelper();

    /**
     * @return null|\MageModule\Core\Model\Data\Mapper
     */
    public function getSystemFieldMapper();

    /**
     * @param \MageModule\Core\Model\Data\Mapper $mapper
     *
     * @return $this
     */
    public function setCustomFieldMapper(\MageModule\Core\Model\Data\Mapper $mapper = null);

    /**
     * @return null|\MageModule\Core\Model\Data\Mapper
     */
    public function getCustomFieldMapper();

    /**
     * @param \MageModule\Core\Model\Data\Formatter\Iterator[] $iterators
     *
     * @return $this
     */
    public function setIterators(array $iterators);

    /**
     * @param string                                         $field
     * @param \MageModule\Core\Model\Data\Formatter\Iterator $iterator
     *
     * @return $this
     */
    public function addIterator($field, \MageModule\Core\Model\Data\Formatter\Iterator $iterator);

    /**
     * @return \MageModule\Core\Model\Data\Formatter\Iterator[]
     */
    public function getIterators();

    /**
     * @param string $field
     *
     * @return \MageModule\Core\Model\Data\Formatter\Iterator|null
     */
    public function getIterator($field);

    /**
     * Acceptable values are string, array, object
     *
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format);

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setGlue($value);

    /**
     * @return string
     */
    public function getGlue();

    /**
     * Set any value or character that should be place at start of formatted string
     *
     * @param null|string $value
     *
     * @return $this
     */
    public function setPrepend($value);

    /**
     * Get value or character that should be place at start of formatted string
     *
     * @return null|string
     */
    public function getPrepend();

    /**
     * Set any value or character that should be place at end of formatted string
     *
     * @param null|string $value
     *
     * @return $this
     */
    public function setAppend($value);

    /**
     * Get value or character that should be place at end of formatted string
     *
     * @return null|string
     */
    public function getAppend();

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function setValueWrapPattern($pattern);

    /**
     * @return string
     */
    public function getValueWrapPattern();

    /**
     * If included fields empty, all fields will be included
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setIncludedFields(array $fields);

    /**
     * If included fields empty, all fields will be included
     *
     * @return array
     */
    public function getIncludedFields();

    /**
     * If included fields empty, all fields will be included
     *
     * @param array $fields
     *
     * @return $this
     */
    public function setExcludedFields(array $fields);

    /**
     * If included fields empty, all fields will be included
     *
     * @return array
     */
    public function getExcludedFields();

    /**
     * Array should be structure $field => $defaultValue
     *
     * @param array $values
     *
     * @return $this
     */
    public function setDefaultValues(array $values);

    /**
     * @return array
     */
    public function getDefaultValues();

    /**
     * @param array|\Magento\Framework\DataObject $item
     *
     * @return string|array|\Magento\Framework\DataObject
     */
    public function format($item);
}
