<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Controller\Adminhtml\Schedule;

use Amasty\CronScheduleList\Controller\Adminhtml\AbstractSchedule;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory;
use Psr\Log\LoggerInterface;

class MassDelete extends AbstractSchedule
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Amasty_CronScheduleList::schedule_list';

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CollectionFactory
     */
    private $jobsCollectionFactory;

    public function __construct(
        Context $context,
        Filter $filter,
        LoggerInterface $logger,
        CollectionFactory $jobsCollectionFactory
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->logger = $logger;
        $this->jobsCollectionFactory = $jobsCollectionFactory;
    }

    /**
     * Mass action execution
     *
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->filter->applySelectionOnTargetProvider();

        /** @var Collection $collection */
        $collection = $this->filter->getCollection($this->jobsCollectionFactory->create());
        $deletedJobs = 0;

        if ($collection->count() > 0) {
            try {
                foreach ($collection->getItems() as $job) {
                    $job->delete();
                    $deletedJobs++;
                }

                $this->messageManager->addSuccessMessage(
                    __('%1 task(s) has been successfully deleted', $deletedJobs)
                );

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error has occurred'));
                $this->logger->critical($e);
            }
        }

        $this->_redirect($this->_redirect->getRefererUrl());
    }
}
