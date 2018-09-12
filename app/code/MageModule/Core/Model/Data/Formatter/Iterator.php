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

namespace MageModule\Core\Model\Data\Formatter;

class Iterator extends \MageModule\Core\Model\Data\Formatter
{
    /**
     * @var \MageModule\Core\Model\Data\FormatterInterface
     */
    private $formatter;

    /**
     * Formatter constructor.
     *
     * @param \Magento\Framework\DataObjectFactory           $objectFactory
     * @param \MageModule\Core\Helper\Data                   $helper
     * @param \MageModule\Core\Model\Data\FormatterInterface $formatter
     * @param \MageModule\Core\Model\Data\Mapper             $systemFieldMapper
     * @param \MageModule\Core\Model\Data\Mapper             $customFieldMapper
     * @param array                                          $iterators
     * @param string                                         $format
     * @param string|array|null                              $glue
     * @param string|array|null                              $prepend
     * @param string|array|null                              $append
     * @param string|null                                    $valueWrapPattern
     * @param array                                          $includedFields
     * @param array                                          $excludedFields
     * @param array                                          $defaultValues
     * @param bool                                           $allowNewlineChar
     * @param bool                                           $allowReturnChar
     * @param bool                                           $allowTabChar
     */
    public function __construct(
        \Magento\Framework\DataObjectFactory $objectFactory,
        \MageModule\Core\Helper\Data $helper,
        \MageModule\Core\Model\Data\FormatterInterface $formatter,
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
        $this->formatter = $formatter;
    }

    /**
     * @param array|\Magento\Framework\DataObject $items
     *
     * @return array|\Magento\Framework\DataObject
     */
    public function iterate($items)
    {
        if (is_array($items)) {
            foreach ($items as &$item) {
                $item = $this->formatter->format($item);
                if ($this->getValueWrapPattern()) {
                    $item = $this->wrapValue(null, $item, $this->getValueWrapPattern());
                }
            }
            $items = $this->append(
                $this->prepend(
                    implode($this->getGlue(), $items),
                    $this->getPrepend()
                ),
                $this->getAppend()
            );
        }

        if ($items instanceof \Magento\Framework\DataObject) {
            $data = $items->getData();
            foreach ($data as &$item) {
                $item = $this->formatter->format($item);
                if ($this->getValueWrapPattern()) {
                    $item = $this->wrapValue(null, $item, $this->getValueWrapPattern());
                }
            }
            $items = $this->append(
                $this->prepend(
                    implode($this->getGlue(), $data),
                    $this->getPrepend()
                ),
                $this->getAppend()
            );
        }

        return $items;
    }
}
