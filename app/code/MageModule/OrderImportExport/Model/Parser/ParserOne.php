<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Parser;

class ParserOne implements \MageModule\OrderImportExport\Model\Parser\ParserInterface
{
    /**
     * @var string
     */
    private $parentTagname;

    /**
     * ParserOne constructor.
     *
     * @param string $parentTagname
     */
    public function __construct($parentTagname)
    {
        $this->parentTagname = $parentTagname;
    }

    /**
     * Items passed into this parser should be in this format. Indentation is not necessary but
     * each element needs to be on a newline just like this example
     *
     *  [[order_item]]
            [[sku]]24-WG086
            [[name]]Sprite Yoga Strap 8 foot
            [[product_type]]grouped
            [[original_price]]17.0000
            [[price]]17.0000
            [[tax_amount]]3.3700
            [[tax_percent]]8.2500
            [[discount_amount]]10.2000
            [[row_total]]51.0000
            [[qty_ordered]]3.0000
            [[qty_invoiced]]0.0000
            [[qty_shipped]]0.0000
            [[qty_backordered]]0
            [[qty_refunded]]0.0000
            [[qty_returned]]0
            [[qty_canceled]]0.0000
            [[options]]
        [[/order_item]]
        [[order_item]]
            [[sku]]24-MB01-test-option3-test-option1-test-option2-test-text
            [[name]]Joust Duffle Bag
            [[product_type]]simple
            [[original_price]]34.0000
            [[price]]193.0000
            [[tax_amount]]38.2100
            [[tax_percent]]8.2500
            [[discount_amount]]115.8000
            [[row_total]]579.0000
            [[qty_ordered]]3.0000
            [[qty_invoiced]]0.0000
            [[qty_shipped]]0.0000
            [[qty_backordered]]0
            [[qty_refunded]]0.0000
            [[qty_returned]]0
            [[qty_canceled]]0.0000
            [[options]][[label]]Test Drop Down[[value]]Option 3[[option_type]]drop_down[[option_value]]3
            [[options]][[label]]Test Multiselect[[value]]Option 1, Option 2[[option_type]]multiple[[option_value]]4,5
            [[options]][[label]]Test Text Option[[value]]some sample text blah[[option_type]]field[[option_value]]some sample text blah
        [[/order_item]]
     *
     *
     * @param string $value
     *
     * @return array
     */
    public function parse($value)
    {
        $result = [];

        $value = $this->stripUnwantedChars($value);
        $items = $this->getAllWrappedByTagname($this->parentTagname, $value);

        $i = 0;
        foreach ($items as &$item) {
            $parts = explode(PHP_EOL, $item);

            foreach ($parts as &$part) {
                $tagname = $this->getTagname($part);
                if ($this->isArray($part)) {
                    $string   = $this->removeTagname($tagname, $part);
                    $array = $this->toArray($string);
                    $result[$i][$tagname][] = $array;
                } else {
                    $result[$i][$tagname] = $this->removeTagname($tagname, $part);
                }
            }

            $i++;
        }

        return $result;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function stripUnwantedChars($value)
    {
        return trim(str_replace(["\t", "\r", '  '], '', $value));
    }

    /**
     * @param string $tagname
     * @param string $value
     *
     * @return array
     */
    private function getAllWrappedByTagname($tagname, $value)
    {
        preg_match_all('/\[\[' . $tagname . '\]\](.*)\[\[\/' . $tagname . '\]\]/misU', $value, $items);
        if (is_array($items)) {
            if (isset($items[1])) {
                if (is_array($items[1])) {
                    foreach ($items[1] as &$item) {
                        $item = trim($item, PHP_EOL);
                    }

                    return $items[1];
                }
            }
        }

        return [];
    }

    /**
     * @param string $value
     *
     * @return array
     */
    private function getTagnames($value)
    {
        preg_match_all('/\[\[(.*)\]\]/U', $value, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return [];
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    private function getTagname($value)
    {
        preg_match('/^\[\[(.*)\]\]/U', $value, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param string $tagname
     * @param string $value
     *
     * @return string
     */
    private function removeTagname($tagname, $value)
    {
        return preg_replace('/^\[\[' . $tagname . '\]\]/U', '', $value, 1);
    }

    /**
     * @param string $value
     *
     * @return array
     */
    private function getValues($value)
    {
        preg_match_all('/\]\](.*)(\[\[|$)/U', $value, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return [];
    }

    /**
     * @param string $value
     *
     * @return array
     */
    private function toArray($value)
    {
        $keys = $this->getTagnames($value);
        $values = $this->getValues($value);

        return array_combine($keys, $values);
    }

    /**
     * @param string $value
     *
     * @return int
     */
    private function isArray($value)
    {
        return preg_match('/^\[\[[^\/].*\]\]\[\[[^\/].*\]\]/U', $value);
    }
}
