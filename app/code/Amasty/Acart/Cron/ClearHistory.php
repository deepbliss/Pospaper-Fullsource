<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Cron;

class ClearHistory
{
    /**
     * @var \Amasty\Acart\Model\Cleaner
     */
    private $cleaner;

    public function __construct(
        \Amasty\Acart\Model\Cleaner $cleaner
    ) {
        $this->cleaner = $cleaner;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $this->cleaner->clearExpiredHistory()->clearExpiredRuleQuotes();

        return $this;
    }
}
