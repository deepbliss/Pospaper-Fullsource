<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Debug\System;

use Amasty\Base\Debug\VarDump;

class Beautifier
{
    /**
     * @var Beautifier
     */
    private static $instance;

    public function __construct()
    {
        VarDump::amastyEcho(Template::$debugJsCss);
    }

    /**
     * @param mixed $var
     */
    public function beautify($var)
    {
        switch (strtolower(gettype($var))) {
            case 'string':
                $result = sprintf(Template::$string, htmlspecialchars($var));
                break;
            case 'object':
                $result = $this->prepareObjectVar($var);
                break;
            case 'array':
                $result = $this->prepareArrayVar($var);
                break;
            case 'boolean':
                $result = sprintf(Template::$var, ($var) ? 'true' : 'false');
                break;
            case 'null':
                $result = sprintf(Template::$var, 'null');
                break;
            default:
                $result = sprintf(Template::$var, htmlspecialchars($var));
                break;
        }

        VarDump::amastyEcho(sprintf(Template::$varWrapper, $result));
    }

    /**
     * @param string|int $key
     *
     * @return string
     */
    private function arrayKey($key)
    {
        if (strtolower(gettype($key)) == 'string') {
                return sprintf(Template::$arrayKeyString, htmlspecialchars($key));
        }

        return sprintf(Template::$arrayKey, htmlspecialchars($key));
    }

    /**
     * @param mixed $var
     *
     * @return string
     */
    private function arraySimpleType($var)
    {
        switch (strtolower(gettype($var))) {
            case 'string':
                return sprintf(Template::$arraySimpleString, htmlspecialchars($var));
                break;
            case 'boolean':
                return sprintf(Template::$arraySimpleVar, $var ? 'true' : 'false');
                break;
            case 'null':
                return sprintf(Template::$arraySimpleVar, 'null');
                break;
            case 'integer':
            case 'float':
            case 'double':
                return sprintf(Template::$arraySimpleVar, htmlspecialchars($var));
                break;
            default:
                return sprintf(Template::$arraySimpleVar, 'Unknown variable type!');
                break;
        }
    }

    /**
     * @param array $arrayVar
     * @param int   $depth
     * @param bool  $opened
     *
     * @return string
     */
    private function prepareArrayVar($arrayVar, $depth = 1, $opened = false)
    {
        $result = sprintf(Template::$arrayHeader, count($arrayVar));
        if ($depth === 1 || $opened) {
            $result .= Template::$arrowsOpened;
        } else {
            $result .= Template::$arrowsClosed;
        }
        foreach ($arrayVar as $arrayKey => $var) {
            switch (strtolower(gettype($var))) {
                case 'array':
                    $result .= sprintf(
                        Template::$array,
                        $depth,
                        $this->arrayKey($arrayKey),
                        $this->prepareArrayVar($var, $depth + 1)
                    );
                    break;
                case 'object':
                    $result .= sprintf(
                        Template::$array,
                        $depth,
                        $this->arrayKey($arrayKey),
                        $this->prepareObjectVar($var, $depth + 1)
                    );
                    break;
                default:
                    $result .= sprintf(
                        Template::$array,
                        $depth,
                        $this->arrayKey($arrayKey),
                        $this->arraySimpleType($var)
                    );
                    break;
            }
        }
        $result .= Template::$arrayFooter;

        return $result;
    }

    /**
     * @param AmastyDump $object
     * @param int $depth
     *
     * @return string
     */
    private function prepareObjectVar($object, $depth = 1)
    {
        $result = sprintf(Template::$objectHeader, $object->className, $object->shortClassName);
        if ($depth === 1) {
            $result .= Template::$arrowsOpened;
        } else {
            $result .= Template::$arrowsClosed;
        }

        $result .= sprintf(Template::$objectMethodHeader, $depth);
        foreach ($object->methods as $method) {
            $result .= sprintf(
                Template::$objectMethod,
                $depth + 1,
                $method
            );
        }
        $result .= Template::$objectMethodFooter;

        $result .= sprintf(
            Template::$array,
            $depth,
            'Properties',
            $this->prepareArrayVar(
                $object->properties,
                $depth + 1,
                true
            )
        );
        $result .= Template::$objectFooter;

        return $result;
    }

    /**
     * @return Beautifier
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Beautifier();
        }

        return self::$instance;
    }
}
