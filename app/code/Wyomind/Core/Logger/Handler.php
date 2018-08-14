<?php

/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Logger;

/**
 * Log handler for Wyomind_Core
 */
class Handler extends \Magento\Framework\Logger\Handler\Base
{

    /**
     * The log file name
     * @var string
     */
    public $fileName = '/var/log/Wyomind_Core.log';
    
    /**
     * The log level
     * @var integer
     */
    public $loggerType = \Monolog\Logger::NOTICE;
    
    /**
     * Class constructor => set the log line format
     * @param \Magento\Framework\Filesystem\DriverInterface $filesystem
     * @param string $filePath
     */
    public function __construct(
        \Magento\Framework\Filesystem\DriverInterface $filesystem,
        $filePath = null
    ) {
        parent::__construct($filesystem, $filePath);
        $this->setFormatter(new \Monolog\Formatter\LineFormatter("[%datetime%] %message%\n", null, true));
    }
    
}
