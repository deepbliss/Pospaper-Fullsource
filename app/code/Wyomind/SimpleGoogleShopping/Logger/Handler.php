<?php

namespace Wyomind\SimpleGoogleShopping\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{

    public $fileName = '/var/log/SimpleGoogleShopping.log';
    public $loggerType = \Monolog\Logger::NOTICE;
}
