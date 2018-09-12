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

namespace MageModule\Core\Exception;

use MageModule\Core\Model\Data\Validator\ResultInterface;

class FieldValidatorException extends \Exception
{
    /**
     * FieldValidatorException constructor.
     *
     * @param string                            $field
     * @param ResultInterface|ResultInterface[] $validators
     * @param int                               $code
     * @param \Exception|null                   $previous
     */
    public function __construct($field, $validators = [], $code = 0, \Exception $previous = null)
    {
        if (is_array($validators)) {
            $messages = [];
            foreach ($validators as $validator) {
                $messages[] = sprintf(
                    'Items contained within the %s field are missing the following fields: %s',
                    $field,
                    implode(', ', $validator->getInvalidData())
                );
            }

            $messages = array_filter($messages, 'strlen');
            $message  = implode(PHP_EOL, $messages);
        } else {
            $message = $validators->getMessage();
        }

        parent::__construct($message, $code, $previous);
    }
}
