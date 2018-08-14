<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Debug;

class VarDie
{
    public static function execute()
    {
        if (VarDump::isAllowed()) {
            foreach (func_get_args() as $var) {
                System\Beautifier::getInstance()->beautify(VarDump::dump($var));
            }
            VarDump::amastyExit();
        }
    }

    public static function backtrace()
    {
        if (VarDump::isAllowed()) {
            $backtrace = debug_backtrace();
            array_shift($backtrace);
            foreach ($backtrace as $route) {
                System\Beautifier::getInstance()->beautify(
                    VarDump::dump(
                        [
                            'action' => $route['class'] . $route['type'] . $route['function'] . '()',
                            'object' => $route['object'],
                            'args' => $route['args'],
                            'file' => $route['file'] . ':' . $route['line']
                        ]
                    )
                );
            }
            VarDump::amastyExit();
        }
    }
}
