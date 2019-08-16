<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Cron;

class RefreshHistory
{
    /**
     * @var \Amasty\Acart\Model\Indexer
     */
    private $indexer;

    public function __construct(
        \Amasty\Acart\Model\Indexer $indexer
    ) {
        $this->indexer = $indexer;
    }

    public function execute()
    {
        $this->indexer->run();
    }
}
