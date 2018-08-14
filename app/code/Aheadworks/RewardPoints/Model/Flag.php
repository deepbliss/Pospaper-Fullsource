<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

/**
 * Class Flag
 *
 * @package Aheadworks\RewardPoints\Model
 */
class Flag extends \Magento\Framework\Flag
{
    /**#@+
     * Constants for reward points flags
     */
    const AW_RP_EXPIRATION_CHECK_LAST_EXEC_TIME = 'aw_rp_expiration_check_last_exec_time';
    const AW_RP_EXPIRATION_REMINDER_LAST_EXEC_TIME = 'aw_rp_expiration_reminder_last_exec_time';
    /**#@-*/

    /**
     * Setter for flag code
     * @codeCoverageIgnore
     *
     * @param string $code
     * @return $this
     */
    public function setRewardPointsFlagCode($code)
    {
        $this->_flagCode = $code;
        return $this;
    }
}
