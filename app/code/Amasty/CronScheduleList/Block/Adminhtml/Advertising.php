<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\Module\Manager;

class Advertising extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Amasty_CronScheduleList::advertising.phtml';

    /**
     * @var Manager
     */
    private $moduleManager;

    public function __construct(
        Manager $moduleManager,
        Template\Context $context,
        array $data = []
    ) {
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        if ($this->moduleManager->isEnabled('Amasty_CronScheduler')) {
            return '';
        }

        return parent::toHtml();
    }

    public function getLink()
    {
        return 'https://amasty.com/cron-scheduler-for-magento-2.html'
            . '?utm_source=extension&utm_medium=backend&utm_campaign=from_cron_schedule_list_to_cron_schedule_m2';
    }
}
