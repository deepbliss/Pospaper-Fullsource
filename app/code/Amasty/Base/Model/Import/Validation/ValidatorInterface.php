<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Validation;

/**
 * @since 1.4.6
 */
interface ValidatorInterface
{
    /**
     * Return array with error codes. Return true on validation pass
     *
     * @param array  $rowData
     * @param string $behavior
     *
     * @throws \Amasty\Base\Exceptions\StopValidation
     * @return array|bool
     */
    public function validateRow(array $rowData, $behavior);

    /**
     * Return array: error_code => error_message
     *
     * @return array
     */
    public function getErrorMessages();
}
