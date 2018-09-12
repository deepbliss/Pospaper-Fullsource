<?php

namespace MageModule\Core\Exception;

use MageModule\Core\Model\Data\Validator\ResultInterface;

class ValidatorException extends \Exception
{
    /**
     * ValidatorException constructor.
     *
     * @param ResultInterface|ResultInterface[] $validators
     * @param int                               $code
     * @param \Exception|null                   $previous
     */
    public function __construct($validators = [], $code = 0, \Exception $previous = null)
    {
        if (is_array($validators)) {
            $messages = [];
            foreach ($validators as $validator) {
                $messages[] = $validator->getMessage();
            }

            $messages = array_filter($messages, 'strlen');
            $message  = implode(PHP_EOL, $messages);
        } else {
            $message = $validators->getMessage();
        }

        parent::__construct($message, $code, $previous);
    }
}
