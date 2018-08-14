<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer;

/**
 * Status renderer
 */
class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    protected $_ioRead = null;
    protected $_coreDate = null;
    protected $_directoryRead = null;

    const _SUCCEED = "SUCCEED";
    const _PENDING = "PENDING";
    const _PROCESSING = "PROCESSING";
    const _HOLD = "HOLD";
    const _FAILED = "FAILED";

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $directoryRead
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Directory\ReadFactory $directoryRead,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_ioRead = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::ROOT);
        $this->_coreDate = $coreDate;
        $this->_directoryRead = $directoryRead->create("");
    }

    /**
     * Renders grid column
     * @param  \Magento\Framework\Object $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $file = 'var/tmp/sgs_' . $row->getSimplegoogleshoppingId() . ".flag";

        if ($this->_directoryRead->isFile($file)) {
            $io = $this->_ioRead->openFile($file, 'r');
            $line = $io->readCsv(0, ";");

            $stats = $io->stat();
            if ($line[0] === self::_PROCESSING) {
                $updatedAt = $stats["mtime"];
                $taskTime = $line[3];

                if ($this->_coreDate->gmtTimestamp() > $updatedAt + ($taskTime * 10)) {
                    $line[0] = 'FAILED';
                } elseif ($this->_coreDate->gmtTimestamp() > $updatedAt + ($taskTime * 2)) {
                    $line[0] = 'HOLD';
                }
            } elseif ($line[0] === self::_SUCCEED) {
                $cron = [];
                $cron['curent']['localTime'] = $this->_coreDate->timestamp();
                $cron['file']['localTime'] = $this->_coreDate->timestamp($stats["mtime"]);
                $cronExpr = json_decode($row->getCronExpr());
                $i = 0;
                if (isset($cronExpr->days)) {
                    foreach ($cronExpr->days as $d) {
                        if (isset($cronExpr->hours)) {
                            foreach ($cronExpr->hours as $h) {
                                $time = explode(':', $h);
                                if (date('l', $cron['curent']['localTime']) == $d) {
                                    $cron['tasks'][$i]['localTime'] = strtotime($this->_coreDate->date('Y-m-d')) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                } else {
                                    $cron['tasks'][$i]['localTime'] = strtotime("last " . $d, $cron['curent']['localTime']) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                }
                                if ($cron['tasks'][$i]['localTime'] >= $cron['file']['localTime'] && $cron['tasks'][$i]['localTime'] <= $cron['curent']['localTime']) {
                                    $line[0] = self::_PENDING;
                                    continue 2;
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
            switch ($line[0]) {
                case self::_SUCCEED:
                    $severity = 'notice';
                    $status = __($line[0]);
                    break;
                case self::_PENDING:
                    $severity = 'minor';
                    $status = __($line[0]);
                    break;
                case self::_PROCESSING:
                    if ($line[2] == "INF") {
                        $line[2] = $line[1];
                    }
                    $percent = round($line[1] * 100 / $line[2]);
                    $severity = 'minor';
                    $status = __($line[0]) . " [" . $percent . "%]";
                    break;
                case self::_HOLD:
                    $severity = 'major';
                    $status = __($line[0]);
                    break;
                case self::_FAILED:
                    $severity = 'critical';
                    $status = __($line[0]);
                    break;
                default:
                    $severity = 'critical';
                    $status = __("ERROR");
                    break;
            }
        } else {
            $severity = 'minor';
            $status = __(self::_PENDING);
        }


        return "<span class='grid-severity-$severity updater' cron='" . $row->getCronExpr() . "' id='feed_" . $row->getSimplegoogleshoppingId() . "'><span>" . ($status) . "</span></span>";
    }
}
