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

namespace MageModule\OrderImportExport\Model\Config;

class Export extends \MageModule\OrderImportExport\Model\AbstractConfig implements
    \MageModule\OrderImportExport\Api\Data\ExportConfigInterface
{
    const ADMIN_RESOURCE = 'MageModule_OrderImportExport::export';
    const XML_PATH       = 'orderimportexport/settings/export';

    /**
     * Export constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface           $scopeConfig
     * @param \Magento\Config\Model\ResourceModel\Config                   $resourceConfig
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param string                                                       $xmlPath
     * @param array                                                        $defaultOptions
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        $xmlPath = self::XML_PATH,
        $defaultOptions = [],
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $scopeConfig,
            $resourceConfig,
            $context,
            $registry,
            $xmlPath,
            $defaultOptions,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->setData(self::DIRECTORY, $directory);

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->getData(self::DIRECTORY);
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->setData(self::FILENAME, $filename);

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->getData(self::FILENAME);
    }

    /**
     * @param string $delimiter
     *
     * @return $this
     */
    public function setDelimiter($delimiter)
    {
        $this->setData(self::DELIMITER, $delimiter);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDelimiter()
    {
        return $this->getData(self::DELIMITER);
    }

    /**
     * @param string $enclosure
     *
     * @return $this
     */
    public function setEnclosure($enclosure)
    {
        $this->setData(self::ENCLOSURE, $enclosure);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnclosure()
    {
        $enclosure = $this->getData(self::ENCLOSURE);
        if ($enclosure === null || $enclosure === '') {
            $enclosure = ' ';
        }

        return $enclosure;
    }

    /**
     * @param string|null $date
     *
     * @return $this
     */
    public function setFrom($date)
    {
        $this->setData(self::FROM, $date);

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->getData(self::FROM);
    }

    /**
     * @param string|null $date
     *
     * @return $this
     */
    public function setTo($date)
    {
        $this->setData(self::TO, $date);

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->getData(self::TO);
    }
}
