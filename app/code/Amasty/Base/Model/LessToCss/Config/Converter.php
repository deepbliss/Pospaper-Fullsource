<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Model\LessToCss\Config;

/**
 * Class Converter
 * @package Amasty\Base\Model\LessToCss\Config
 */
class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**#@+
     * Constants defined for keys of data array
     */
    const HANDLES = "handles";
    const CSS_OPTIONS = "cssOptions";
    const CSS_OPTION_FILENAME = "fileName";
    const CSS_OPTION_PATH = "path";
    const IFCONFIG = "ifconfig";
    /**#@-*/

    /**
     * Convert dom node tree to array
     *
     * @param \DOMDocument $source
     *
     * @return array = [string => [
     *         'handles' => [string],
     *         'ifconfig' => [string],
     *         'cssOptions' => ['fileName' => string, 'path' => string]
     *     ]]
     */
    public function convert($source)
    {
        $output = [];
        if (!$source instanceof \DOMDocument) {
            return $output;
        }

        /** @var \DOMNodeList $types */
        $types = $source->getElementsByTagName('module');
        /** @var \DOMNode $type */
        foreach ($types as $type) {
            $moduleConfiguration = [];
            $moduleName = $type->getAttribute('name');

            $handles = $type->getElementsByTagName('handle');
            foreach ($handles as $handle) {
                $handleName = $handle->getAttribute('name');
                $moduleConfiguration[self::HANDLES][$handleName] = $handleName;
            }

            $ifconfigs = $type->getElementsByTagName('ifconfig');
            $moduleConfiguration[self::IFCONFIG] = [];
            foreach ($ifconfigs as $ifconfig) {
                $moduleConfiguration[self::IFCONFIG][] = $ifconfig->nodeValue;
            }

            $cssConfigurations = $type->getElementsByTagName('cssOptions');
            if (!$cssConfigurations->length) {
                $moduleConfiguration[self::CSS_OPTIONS] = [
                    self::CSS_OPTION_FILENAME => 'styles',
                    self::CSS_OPTION_PATH => 'css'
                ];
            } else {
                foreach ($cssConfigurations as $row) {
                    $fileName = $row->getAttribute('fileName');
                    if (!$fileName) {
                        $fileName = 'styles';
                    }
                    $pathToLess = $row->getAttribute('path');
                    if (!$pathToLess) {
                        $pathToLess = 'css';
                    }
                    $moduleConfiguration[self::CSS_OPTIONS] = [
                        self::CSS_OPTION_FILENAME => $fileName,
                        self::CSS_OPTION_PATH => $pathToLess
                    ];
                }
            }

            $output[$moduleName] = $moduleConfiguration;
        }

        return $output;
    }
}
