<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Debug;

class VarDump
{
    /**
     * @var array
     */
    private static $addressPath = [
        'REMOTE_ADDR',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR'
    ];

    /**
     * @var array
     */
    private static $amastyIps = [
        '213.184.226.82',
        '87.252.238.217'
    ];

    /**
     * @var int
     */
    private static $objectDepthLevel = 2;

    /**
     * @var int
     */
    private static $arrayDepthLevel = 2;

    /**
     * @param int $level
     */
    public static function setObjectDepthLevel($level)
    {
        self::$objectDepthLevel = (int)$level;
    }

    /**
     * @param int $level
     */
    public static function setArrayDepthLevel($level)
    {
        self::$arrayDepthLevel = (int)$level;
    }

    public static function execute()
    {
        if (self::isAllowed()) {
            foreach (func_get_args() as $var) {
                System\Beautifier::getInstance()->beautify(self::dump($var));
            }
        }
    }

    /**
     * @param array $array
     * @param int   $level
     *
     * @return array|string
     */
    private static function castArray($array, $level)
    {
        if ($level > self::$arrayDepthLevel) {
            return '...';
        }
        $result = [];
        foreach ($array as $key => $value) {
            switch (strtolower(gettype($value))) {
                case 'object':
                    $result[$key] = self::castObject($value, $level + 1);
                    break;
                case 'array':
                    $result[$key] = self::castArray($value, $level + 1);
                    break;
                default:
                    $result[$key] = $value;
                    break;
            }
        }
        return $result;
    }

    /**
     * @param     $object
     * @param int $level
     *
     * @return System\AmastyDump|string
     */
    private static function castObject($object, $level)
    {
        if ($level > self::$objectDepthLevel) {
            return '...';
        }
        $reflection = new \ReflectionClass($object);

        $amastyDump = new System\AmastyDump();

        $amastyDump->className = $reflection->getName();
        $amastyDump->shortClassName = $reflection->getShortName();

        foreach ($reflection->getMethods() as $method) {
            $amastyDump->methods[] = $method->getName();
        }
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);
            switch (strtolower(gettype($propertyValue))) {
                case 'object':
                    $amastyDump->properties[$property->name] = self::castObject($propertyValue, $level + 1);
                    break;
                case 'array':
                    $amastyDump->properties[$property->name] = self::castArray($propertyValue, $level + 1);
                    break;
                default:
                    $amastyDump->properties[$property->name] = $propertyValue;
                    break;
            }
        }
        return $amastyDump;
    }

    /**
     * @param mixed $var
     *
     * @return AmastyDump|array|mixed
     */
    public static function dump($var)
    {
        if (self::isAllowed()) {
            switch (strtolower(gettype($var))) {
                case 'object':
                    return self::castObject($var, 0);
                case 'array':
                    return self::castArray($var, 0);
                case 'resource':
                    return stream_get_meta_data($var);
                default:
                    return $var;
            }
        }
    }

    /**
     * Check for Amasty Ips
     *
     * @return bool
     */
    public static function isAllowed()
    {
        foreach (self::$addressPath as $path) {
            if (!empty($_SERVER[$path]) && in_array($_SERVER[$path], self::$amastyIps)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $code
     */
    public static function amastyExit($code = 0)
    {
        if (self::isAllowed()) {
            $exit = create_function('$code', 'exit($code);');
            $exit($code);
        }
    }

    /**
     * @param string $string
     */
    public static function amastyEcho($string)
    {
        if (self::isAllowed()) {
            $echo = create_function('$string', 'echo($string);');
            $echo($string);
        }
    }
}
