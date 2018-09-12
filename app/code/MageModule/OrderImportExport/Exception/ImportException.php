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

namespace MageModule\OrderImportExport\Exception;

class ImportException extends \Exception
{
    const IMPORT_STATUS_NO  = 0;
    const IMPORT_STATUS_YES = 1;

    /**
     * @var int
     */
    private $imported;

    /**
     * ImportException constructor.
     *
     * @param int             $imported
     * @param string          $message
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($imported, $message = "", $code = 0, \Exception $previous = null)
    {
        $this->imported = (int)$imported;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return bool
     */
    public function isImported()
    {
        return $this->imported === self::IMPORT_STATUS_YES;
    }

    /**
     * @return bool
     */
    public function isNotImported()
    {
        return $this->imported === self::IMPORT_STATUS_NO;
    }
}
