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
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Model\Data\Validator;

class Result implements \MageModule\Core\Model\Data\Validator\ResultInterface
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var array
     */
    private $invalidData;

    /**
     * Result constructor.
     *
     * @param bool        $isValid
     * @param null|string $message
     * @param array       $invalidData
     */
    public function __construct(
        $isValid,
        $message = null,
        array $invalidData = []
    ) {
        $this->isValid     = $isValid;
        $this->message     = $message;
        $this->invalidData = $invalidData;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (bool)$this->isValid;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getInvalidData()
    {
        return $this->invalidData;
    }
}
