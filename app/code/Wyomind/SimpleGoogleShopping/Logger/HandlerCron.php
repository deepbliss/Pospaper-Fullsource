<?php

namespace Wyomind\SimpleGoogleShopping\Logger;

class HandlerCron extends \Magento\Framework\Logger\Handler\Base
{

    public $fileName = '/var/log/SimpleGoogleShopping-cron.log';
    public $loggerType = \Monolog\Logger::NOTICE;
}
