<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Ui\DataProvider\Listing;

class ScheduleDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Amasty\CronScheduleList\Model\ScheduleCollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        \Amasty\CronScheduleList\Model\ScheduleCollectionFactory $collectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collectionFactory = $collectionFactory;
    }

    public function getCollection()
    {
        if (!$this->collection) {
            $this->collection = $this->collectionFactory->create()->removeActivitySchedule();
        }

        return $this->collection;
    }
}
