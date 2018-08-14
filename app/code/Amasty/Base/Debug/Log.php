<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Debug;

class Log
{
    /**
     * @var \Magento\Framework\Logger\Monolog
     */
    private static $loggerInstance;

    /**
     * @var string
     */
    private static $fileToLog = 'amasty_debug.log';

    public static function execute()
    {
        if (VarDump::isAllowed()) {
            VarDump::setObjectDepthLevel(1);
            foreach (func_get_args() as $var) {
                self::logToFile(
                    System\LogBeautifier::getInstance()->beautify(
                        VarDump::dump($var)
                    )
                );
            }
        }
    }

    /**
     * @param string $filename
     */
    public static function setLogFile($filename)
    {
        if (preg_match('/^[a-z_]+\.log$/i', $filename)) {
            self::$fileToLog = $filename;
        }
    }

    /**
     * Log debug_backtrace
     */
    public static function backtrace()
    {
        if (VarDump::isAllowed()) {
            $backtrace = debug_backtrace();
            array_shift($backtrace);
            foreach ($backtrace as $key => $route) {
                $backtrace[$key] = [
                    'action' => $route['class'] . $route['type'] . $route['function'] . '()',
                    'file' => $route['file'] . ':' . $route['line']
                ];
            }
            self::logToFile(System\LogBeautifier::getInstance()->beautify(VarDump::dump($backtrace)));
        }
    }

    /**
     * @param string $var
     */
    private static function logToFile($var)
    {
        self::getLogger()->addRecord(200, $var);
    }

    /**
     * @return \Magento\Framework\Logger\Monolog
     */
    private static function getLogger()
    {
        if (!self::$loggerInstance) {
            self::configureInstance();
        }
        return self::$loggerInstance;
    }

    private static function configureInstance()
    {
        $logDir = \Magento\Framework\App\ObjectManager::getInstance()
            ->get('\Magento\Framework\Filesystem\DirectoryList')
            ->getPath('log');
        $handler = new \Monolog\Handler\RotatingFileHandler($logDir . DIRECTORY_SEPARATOR . self::$fileToLog, 2);

        $output = "\n----------------------------------------------------------------------------\n%datetime%\n
%message%
----------------------------------------------------------------------------\n\n";
        $formatter = new System\AmastyFormatter($output);

        $handler->setFormatter($formatter);
        self::$loggerInstance = new \Magento\Framework\Logger\Monolog('amasty_logger', [$handler]);
    }
}
