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

namespace MageModule\OrderImportExport\Model\Processor;

abstract class AbstractProcessor
{
    /**
     * @var array
     */
    private $excludedFields;

    /**
     * @var \MageModule\OrderImportExport\Api\Data\ImportConfigInterface
     */
    private $config;

    /**
     * Order constructor.
     *
     * @param array $excludedFields
     */
    public function __construct($excludedFields = [])
    {
        $this->excludedFields = $excludedFields;
        if ($excludedFields && current($excludedFields) === null) {
            $this->excludedFields = array_keys($excludedFields);
        }
    }

    /**
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config
     *
     * @return $this
     */
    public function setConfig(\MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \MageModule\OrderImportExport\Api\Data\ImportConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param object|array $item
     *
     * @return $this
     */
    protected function removeExcludedFields(&$item)
    {
        if (is_object($item) && method_exists($item, 'unsetData')) {
            foreach ($this->excludedFields as $field) {
                $item->unsetData($field);
            }
        } elseif (is_array($item)) {
            foreach ($this->excludedFields as $field) {
                if (array_key_exists($field, $item)) {
                    unset($item[$field]);
                }
            }
        }

        return $this;
    }
}
