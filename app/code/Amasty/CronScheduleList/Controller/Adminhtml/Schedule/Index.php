<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Controller\Adminhtml\Schedule;

use Amasty\CronScheduleList\Controller\Adminhtml\AbstractSchedule;
use Magento\Framework\Controller\ResultFactory;

class Index extends AbstractSchedule
{
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Amasty_CronScheduleList::schedule_list');
        $resultPage->getConfig()->getTitle()->prepend(__('Cron Tasks List'));
        $resultPage->addBreadcrumb(__('Cron Tasks List'), __('Cron Tasks List'));

        return $resultPage;
    }
}
