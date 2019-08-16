<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Exceptions;

/**
 * Class WrongValidatorInterface
 * @package Amasty\Base\Exceptions
 */
class WrongValidatorInterface extends \Magento\Framework\Exception\LocalizedException
{
    /**
     * @param \Magento\Framework\Phrase $phrase
     * @param \Exception $cause
     * @param int $code
     */
    public function __construct(\Magento\Framework\Phrase $phrase = null, \Exception $cause = null, $code = 0)
    {
        if (!$phrase) {
            $phrase = __('Wrong Validator Interface.');
        }
        parent::__construct($phrase, $cause, (int) $code);
    }
}
