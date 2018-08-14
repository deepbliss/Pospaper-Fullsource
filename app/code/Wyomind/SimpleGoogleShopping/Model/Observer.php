<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model;

/**
 * Simple Google Shopping observer
 */
class Observer
{

    protected $_collectionFactory = null;
    protected $_coreDate = null;
    protected $_transportBuilder;
    protected $_logger = null;
    protected $_coreHelper = null;

    /**
     * Initialize the cron schedule manager
     * @param \Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Wyomind\Core\Helper\Log $logHelper
     * @param \Wyomind\Core\Helper\Data $coreHelper
     */
    public function __construct(
        \Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Wyomind\SimpleGoogleShopping\Logger\LoggerCron $logger,
        \Wyomind\Core\Helper\Data $coreHelper
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_coreDate = $coreDate;
        $this->_transportBuilder = $transportBuilder;
        $this->_logger = $logger;
        $this->_coreHelper = $coreHelper;
    }

    /**
     * Check if google shopping data feeds need to be generated
     * @param \Magento\Cron\Model\Schedule $schedule
     */
    public function checkToGenerate(\Magento\Cron\Model\Schedule $schedule)
    {

        try {
            $log = [];

            $this->_logger->notice("-------------------- CRON PROCESS --------------------");
            $log[] = "-------------------- CRON PROCESS --------------------";

            $coll = $this->_collectionFactory->create();

            $cnt = 0;

            $first = true;

            foreach ($coll as $feed) {
                $done = false;
                try {
                    $feed->isCron = true;
                    if ($first) {
                        $feed->loadCustomFunctions();
                        $first = false;
                    }

                    $this->_logger->notice("--> Running profile : " . $feed->getSimplegoogleshoppingFilename() . " [#" . $feed->getSimplegoogleshoppingId() . "] <--");
                    $log[] = "--> Running profile : " . $feed->getSimplegoogleshoppingFilename() . " [#" . $feed->getSimplegoogleshoppingId() . "] <--";

                    $cron = [];

                    $cron['curent']['localDate'] = $this->_coreDate->date('l Y-m-d H:i:s');
                    $cron['curent']['gmtDate'] = $this->_coreDate->gmtDate('l Y-m-d H:i:s');
                    $cron['curent']['localTime'] = $this->_coreDate->timestamp();
                    $cron['curent']['gmtTime'] = $this->_coreDate->gmtTimestamp();

                    $cron['file']['localDate'] = $this->_coreDate->date('l Y-m-d H:i:s', $feed->getSimplegoogleshoppingTime());
                    $cron['file']['gmtDate'] = $feed->getSimplegoogleshoppingTime();
                    $cron['file']['localTime'] = $this->_coreDate->timestamp($feed->getSimplegoogleshoppingTime());
                    $cron['file']['gmtTime'] = strtotime($feed->getSimplegoogleshoppingTime());


                    $cron['offset'] = $this->_coreDate->getGmtOffset("hours");

                    $log[] = '   * Last update : ' . $cron['file']['gmtDate'] . " GMT / " . $cron['file']['localDate'] . ' GMT' . $cron['offset'] . "";
                    $log[] = '   * Current date : ' . $cron['curent']['gmtDate'] . " GMT / " . $cron['curent']['localDate'] . ' GMT' . $cron['offset'] . "";
                    $this->_logger->notice('   * Last update : ' . $cron['file']['gmtDate'] . " GMT / " . $cron['file']['localDate'] . ' GMT' . $cron['offset']);
                    $this->_logger->notice('   * Current date : ' . $cron['curent']['gmtDate'] . " GMT / " . $cron['curent']['localDate'] . ' GMT' . $cron['offset']);

                    $cronExpr = json_decode($feed->getCronExpr());

                    $i = 0;

                    if ($cronExpr != null && isset($cronExpr->days)) {
                        foreach ($cronExpr->days as $d) {
                            foreach ($cronExpr->hours as $h) {
                                $time = explode(':', $h);
                                if (date('l', $cron['curent']['gmtTime']) == $d) {
                                    $cron['tasks'][$i]['localTime'] = strtotime($this->_coreDate->date('Y-m-d')) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                    $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                                } else {
                                    $cron['tasks'][$i]['localTime'] = strtotime("last " . $d, $cron['curent']['localTime']) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                    $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                                }

                                if ($cron['tasks'][$i]['localTime'] >= $cron['file']['localTime'] && $cron['tasks'][$i]['localTime'] <= $cron['curent']['localTime'] && $done != true) {
                                    $this->_logger->notice('   * Scheduled : ' . ($cron['tasks'][$i]['localDate'] . " GMT" . $cron['offset']));
                                    $log[] = '   * Scheduled : ' . ($cron['tasks'][$i]['localDate'] . " GMT" . $cron['offset']) . "";
                                    $this->_logger->notice("   * Starting generation");
                                    $result = $feed->generateXml();
                                    if ($result === $feed) {
                                        $done = true;
                                        $this->_logger->notice("   * EXECUTED!");
                                        $log[] = "   * EXECUTED!";
                                    } else {
                                        $this->_logger->notice("   * ERROR! " . $result);
                                        $log[] = "   * ERROR! " . $result . "";
                                    }
                                    $cnt++;
                                    break 2;
                                }

                                $i++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $cnt++;
                    $this->_logger->notice("   * ERROR! " . ($e->getMessage()));
                    $log[] = "   * ERROR! " . ($e->getMessage()) . "";
                }
                if (!$done) {
                    $this->_logger->notice("   * SKIPPED!");
                    $log[] = "   * SKIPPED!";
                }
            }


            if ($this->_coreHelper->getStoreConfig("simplegoogleshopping/settings/enable_reporting")) {
                $emails = explode(',', $this->_coreHelper->getStoreConfig("simplegoogleshopping/settings/emails"));
                if (count($emails) > 0) {
                    try {
                        if ($cnt) {
                            $template = "wyomind_simplegoogleshopping_cron_report";

                            $transport = $this->_transportBuilder
                                ->setTemplateIdentifier($template)
                                ->setTemplateOptions(
                                    [
                                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                                    ]
                                )
                                ->setTemplateVars(
                                    [
                                        'report' => implode("<br/>", $log),
                                        'subject' => $this->_coreHelper->getStoreConfig('simplegoogleshopping/settings/report_title')
                                    ]
                                )
                                ->setFrom(
                                    [
                                        'email' => $this->_coreHelper->getStoreConfig('simplegoogleshopping/settings/sender_email'),
                                        'name' => $this->_coreHelper->getStoreConfig('simplegoogleshopping/settings/sender_name')
                                    ]
                                )
                                ->addTo($emails[0]);

                            $count = count($emails);
                            for ($i = 1; $i < $count; $i++) {
                                $transport->addCc($emails[$i]);
                            }

                            $transport->getTransport()->sendMessage();
                        }
                    } catch (\Exception $e) {
                        $this->_logger->notice('   * EMAIL ERROR! ' . $e->getMessage());
                        $log[] = '   * EMAIL ERROR! ' . ($e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            $schedule->setStatus('failed');
            $schedule->setMessage($e->getMessage());
            $schedule->save();
            $this->_logger->notice("MASSIVE ERROR ! ");
            $this->_logger->notice($e->getMessage());
        }
    }

}
