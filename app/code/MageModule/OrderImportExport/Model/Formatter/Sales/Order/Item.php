<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Formatter\Sales\Order;

class Item extends \MageModule\Core\Model\Data\Formatter
{
    /**
     * @var \MageModule\Core\Model\Data\FormatterInterface[]
     */
    private $formatters;

    /**
     * Formatter constructor.
     *
     * @param \Magento\Framework\DataObjectFactory             $objectFactory
     * @param \MageModule\Core\Helper\Data                     $helper
     * @param \MageModule\Core\Model\Data\Mapper               $systemFieldMapper
     * @param \MageModule\Core\Model\Data\Mapper               $customFieldMapper
     * @param array                                            $iterators
     * @param string                                           $format
     * @param string|array|null                                $glue
     * @param string|array|null                                $prepend
     * @param string|array|null                                $append
     * @param string|null                                      $valueWrapPattern
     * @param array                                            $includedFields
     * @param array                                            $excludedFields
     * @param array                                            $defaultValues
     * @param bool                                             $allowNewlineChar
     * @param bool                                             $allowReturnChar
     * @param bool                                             $allowTabChar
     * @param \MageModule\Core\Model\Data\FormatterInterface[] $formatters
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
        $allowTabChar = true,
        array $formatters = []
    ) {
        parent::__construct(
            $objectFactory,
            $helper,
            $systemFieldMapper,
            $customFieldMapper,
            $iterators,
            $format,
            $glue,
            $prepend,
            $append,
            $valueWrapPattern,
            $includedFields,
            $excludedFields,
            $defaultValues,
            $allowNewlineChar,
            $allowReturnChar,
            $allowTabChar
        );

        $this->setFormatters($formatters);
    }

    /**
     * @param array $formatters
     *
     * @return $this
     */
    public function setFormatters(array $formatters)
    {
        $this->formatters = $formatters;

        return $this;
    }

    /**
     * @param string                                         $type
     * @param \MageModule\Core\Model\Data\FormatterInterface $formatter
     *
     * @return $this
     */
    public function addFormatter(
        $type,
        \MageModule\Core\Model\Data\FormatterInterface $formatter
    ) {
        $this->formatters[$type] = $formatter;

        return $this;
    }

    /**
     * @return array|\MageModule\Core\Model\Data\FormatterInterface[]
     */
    public function getFormatters()
    {
        return $this->formatters;
    }

    /**
     * @param string $type
     *
     * @return bool|\MageModule\Core\Model\Data\FormatterInterface|mixed
     */
    public function getFormatter($type)
    {
        if (isset($this->formatters[$type])) {
            return $this->formatters[$type];
        }

        return false;
    }

    /**
     * @param array|\Magento\Framework\DataObject $item
     *
     * @return null|string
     */
    public function format($item)
    {
        $formatter = $this->getFormatter($item->getProductType());
        if ($formatter instanceof \MageModule\Core\Model\Data\FormatterInterface) {
            return $formatter->format($item);
        }

        return null;
    }
}
