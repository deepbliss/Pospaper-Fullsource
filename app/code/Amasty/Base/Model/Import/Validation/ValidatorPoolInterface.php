<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\Import\Validation;

interface ValidatorPoolInterface
{
    /**
     * @return \Amasty\Base\Model\Import\Validation\ValidatorInterface[]
     */
    public function getValidators();

    /**
     * @param \Amasty\Base\Model\Import\Validation\ValidatorInterface
     *
     * @return void
     */
    public function addValidator($validator);
}
