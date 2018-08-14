<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Simplegoogleshopping general helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_storeManager = null;
    protected $_list = null;
    protected $_coreHelper;
    protected $_io;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManager $storeManager
     * @param \Magento\Framework\App\Filesystem\DirectoryList $list
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param \Magento\Framework\Filesystem\Io\File $io
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\App\Filesystem\DirectoryList $list,
        \Wyomind\Core\Helper\Data $coreHelper,
        \Magento\Framework\Filesystem\Io\File $io
    )
    {

        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->_list = $list;
        $this->_coreHelper = $coreHelper;
        $this->_io = $io;
    }

    /**
     * Generates the html error report summary after a data feed generation
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $row
     * @return string
     */
    public function generationStats($row)
    {


        $fileName = preg_replace('/^\//', '', $row->getSimplegoogleshoppingPath() . ($row->getSimplegoogleshoppingPath() == "/" ? "" : "/") . $row->getSimplegoogleshoppingFilename());

        $this->_storeManager->setCurrentStore($row->getStoreId());
        $baseurl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, false);
        $rootdir = $this->_list->getPath(\Magento\Framework\App\Filesystem\DirectoryList::ROOT);
        $url = $baseurl . $fileName;
        $url = preg_replace('/([^\:])\/\//', '$1/', $url);

        if ($this->_io->fileExists($rootdir . "/" . $fileName)) {
            $report = unserialize($row->getSimplegoogleshoppingReport());
            $errors = 0;
            if (isset($report['required'])) {
                $errors += count($report['required']);
            }
            if (isset($report['toolong'])) {
                $errors += count($report['toolong']);
            }
            if (isset($report['toomany'])) {
                $errors += count($report['toomany']);
            }
            if (isset($report['invalid'])) {
                $errors += count($report['invalid']);
            }
            $warnings = 0;
            if (isset($report['recommended'])) {
                $warnings += count($report['recommended']);
            }
            $time = $report['stats'][1];
            $items = $report['stats'][0];


            $stats = $items . __(' product') . ($items > 1 ? "s" : "") . __(" exported in ") . $this->_coreHelper->getDuration($time);

            if ($report == null) {
                return '<a href="' . $url . '?r=' . time() . '" target="_blank">' . $url . '</a><br>'
                    . "[ " . __('The data feed must be generated prior to any report.') . " ]";
            } elseif (!($errors + $warnings)) {
                return '<a href="' . $url . '?r=' . time() . '" target="_blank">' . $url . '</a><br>'
                    . '[ ' . $stats . ", " . __('no error detected') . ' ]';
            } else {
                return '<a href="' . $url . '?r=' . time() . '" target="_blank">' . $url . '</a><br>'
                    . '[ ' . $stats . ", " . $errors . " " . __('error') . ($errors > 1 ? "s" : null) . ', ' . $warnings . ' ' . __('warning') . ($warnings > 1 ? "s" : null) . ' ]';
            }
        } else {
            return $url . "<br> [ " . __('no report available') . " ]";
        }
    }

    /**
     * Generates the html error report after a data feed generation
     * @param array $data
     * @return string
     */
    public function reportToHtml($data)
    {

        $notice = __("This report does not replace the error report from Google and is by no means a guarantee that your data feed will be approved by the Google team.");
        $html = null;

        $types = ["required", "recommended", "toomany", "toolong", "invalid"];

        foreach ($types as $type) {
            if (isset($data[$type])) {
                foreach ($data[$type] as $error) {
                    $html .= "<h3 " . ($type == "recommended" ? " style='color:orangered'" : "") . ">" . $error['message'] . " [" . $error['count'] . " " . __("items") . "]</h3>";
                    if (isset($error['skus']) && $error['skus'] != "") {
                        $html .= "<div class='exemples'>" . __("Examples:") . " <b>" . $error['skus'] . "</b></div>";
                    }
                }
            }
        }

        if ($data == null) {
            return "<div id='sgs-report'>" . $html . "<br><br><b>" . __('The data feed must be generated prior to any report.') . "</b></div>";
        } elseif ($html !== null) {
            return "<div id='sgs-report'>" . $notice . $html . "</div>";
        } else {
            return "<div id='sgs-report'>" . $notice . "<br><br><b>" . __('no error detected.') . "</b></div>";
        }
    }

    public function stripTagsContent(
        $text,
        $tags = '',
        $invert = false
    )
    {


        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if (is_array($tags) and count($tags) > 0) {
            if ($invert == false) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == false) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return strip_tags($text);
    }

    public function strReplaceFirst(
        $search,
        $replace,
        $subject
    )
    {

        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }
}
