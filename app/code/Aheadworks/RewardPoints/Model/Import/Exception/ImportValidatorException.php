<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Import\Exception;

use Aheadworks\RewardPoints\Api\Exception\ImportValidatorExceptionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ImportValidatorException
 *
 * @package Aheadworks\RewardPoints\Model\Import\Exception
 */
class ImportValidatorException extends LocalizedException implements ImportValidatorExceptionInterface
{
}
