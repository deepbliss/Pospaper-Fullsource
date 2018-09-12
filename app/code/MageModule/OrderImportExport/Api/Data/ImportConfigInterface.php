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

namespace MageModule\OrderImportExport\Api\Data;

interface ImportConfigInterface
{
    const DELIMITER           = 'delimiter';
    const ENCLOSURE           = 'enclosure';
    const IMPORT_ORDER_NUMBER = 'import_order_number';
    const CREATE_INVOICE      = 'create_invoice';
    const CREATE_SHIPMENT     = 'create_shipment';
    const CREATE_CREDIT_MEMO  = 'create_credit_memo';
    const ERROR_LIMIT = 'error_limit';

    /**
     * @param string $delimiter
     *
     * @return $this
     */
    public function setDelimiter($delimiter);

    /**
     * @return string
     */
    public function getDelimiter();

    /**
     * @param string $enclosure
     *
     * @return $this
     */
    public function setEnclosure($enclosure);

    /**
     * @return string
     */
    public function getEnclosure();

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setImportOrderNumber($bool);

    /**
     * @return int
     */
    public function getImportOrderNumber();

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateInvoice($bool);

    /**
     * @return int
     */
    public function getCreateInvoice();

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateShipment($bool);

    /**
     * @return int
     */
    public function getCreateShipment();

    /**
     * @param int|bool $bool
     *
     * @return $this
     */
    public function setCreateCreditMemo($bool);

    /**
     * @return int
     */
    public function getCreateCreditMemo();

    /**
     * @param int $int
     *
     * @return $this
     */
    public function setErrorLimit($int);

    /**
     * @return int
     */
    public function getErrorLimit();
}
