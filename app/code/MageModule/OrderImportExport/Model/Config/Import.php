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

class Import extends \MageModule\OrderImportExport\Model\AbstractConfig implements
    \MageModule\OrderImportExport\Api\Data\ImportConfigInterface
{
    const ADMIN_RESOURCE = 'MageModule_OrderImportExport::import';
    const XML_PATH       = 'orderimportexport/settings/import';

    /**
     * Import constructor.
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
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setImportOrderNumber($bool)
    {
        $this->setData(self::IMPORT_ORDER_NUMBER, (int)$bool);

        return $this;
    }

    /**
     * @return int
     */
    public function getImportOrderNumber()
    {
        return (int)$this->getData(self::IMPORT_ORDER_NUMBER);
    }

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateInvoice($bool)
    {
        $this->setData(self::CREATE_INVOICE, (int)$bool);

        if (!$bool) {
            $this->setCreateCreditMemo(false);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCreateInvoice()
    {
        return (int)$this->getData(self::CREATE_INVOICE);
    }

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateShipment($bool)
    {
        $this->setData(self::CREATE_SHIPMENT, (int)$bool);

        return $this;
    }

    /**
     * @return int
     */
    public function getCreateShipment()
    {
        return (int)$this->getData(self::CREATE_SHIPMENT);
    }

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateCreditMemo($bool)
    {
        $this->setData(self::CREATE_CREDIT_MEMO, (int)$bool);

        return $this;
    }

    /**
     * @return int
     */
    public function getCreateCreditMemo()
    {
        $result = (int)$this->getData(self::CREATE_CREDIT_MEMO);
        if (!$this->getCreateInvoice()) {
            $result = 0;
        }

        return $result;
    }

    /**
     * @param int $int
     *
     * @return $this
     */
    public function setErrorLimit($int)
    {
        $this->setData(self::ERROR_LIMIT, (int)$int);

        return $this;
    }

    /**
     * @return int
     */
    public function getErrorLimit()
    {
        return (int)$this->getData(self::ERROR_LIMIT);
    }

    /**
     * @return $this
     */
    public function saveConfig()
    {
        $this->unsetData('file');

        return parent::saveConfig();
    }
}
