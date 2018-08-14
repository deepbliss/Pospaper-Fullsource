<?php

namespace Nextopia\Search\Logger;

use Monolog\Logger as MLogger;
use Magento\Framework\Logger\Handler\Base;

class Handler extends Base {

    /**
     * Logging level
     * @var int
     */
    protected $loggerType = MLogger::DEBUG;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/nextopia-cron-exporter.log';

}
