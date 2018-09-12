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

interface ExportConfigInterface
{
    const DIRECTORY = 'directory';
    const FILENAME  = 'filename';
    const DELIMITER = 'delimiter';
    const ENCLOSURE = 'enclosure';
    const FROM      = 'from';
    const TO        = 'to';

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory);

    /**
     * @return string
     */
    public function getDirectory();

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename($filename);

    /**
     * @return string
     */
    public function getFilename();

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
     * @param string|null $date
     *
     * @return $this
     */
    public function setFrom($date);

    /**
     * @return string
     */
    public function getFrom();

    /**
     * @param string|null $date
     *
     * @return $this
     */
    public function setTo($date);

    /**
     * @return string
     */
    public function getTo();
}
