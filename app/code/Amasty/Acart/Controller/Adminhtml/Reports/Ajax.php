<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Controller\Adminhtml\Reports;

use Amasty\Acart\Block\Adminhtml\Reports\Filters;
use Amasty\Acart\Model\Date;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Amasty\Acart\Model\StatisticsManagement;

class Ajax extends Action
{
    /**#@+*/
    const ABANDONMENT_RATE = 'rated-total';
    const EMAILS_SENT = 'sent-total';
    const CARTS_RESTORED = 'restored-total';
    const ORDERS_PLACED = 'placed-total';
    const POTENTIAL_REVENUE = 'potential-revenue';
    const RECOVERED_REVENUE = 'recovered-revenue';
    const EFFICIENCY = 'efficiency';
    const PERCENT = 100;
    /**#@-*/

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var StatisticsManagement
     */
    private $statisticsManagement;

    /**
     * @var Date
     */
    private $date;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Action\Context $context,
        JsonFactory $resultJsonFactory,
        StatisticsManagement $statisticsManagement,
        Date $date,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->statisticsManagement = $statisticsManagement;
        $this->date = $date;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $response['type'] = 'success';

        try {
            $response['data'] = $this->loadDataByParams();
        } catch (LocalizedException $exception) {
            $response['type'] = 'warning';
            $response['message'] = $exception->getMessage();
        } catch (\Exception $exception) {
            $response['type'] = 'error';
            $response['message'] = __('Something went wrong. Please try again or check your Magento log file.');

            $this->logger->error($exception->getMessage());
        }

        return $this->resultJsonFactory->create()->setData($response);
    }

    /**
     * @return array
     */
    private function loadDataByParams()
    {
        $data = [];
        list($websiteId, $dateRange, $dateFrom, $dateTo) = $this->getParamsFromRequest();
        list($dateFrom, $dateTo) = $this->getDate($dateRange, $dateFrom, $dateTo);

        $storeIds = $this->getStoreIdsByWebsiteId($websiteId);

        $data[self::ABANDONMENT_RATE] = $this->statisticsManagement->getAbandonmentRate(
            $storeIds,
            $dateTo,
            $dateFrom
        ) . '%';

        $data[self::POTENTIAL_REVENUE] = $this->statisticsManagement->getTotalAbandonedMoney(
            $storeIds,
            $dateTo,
            $dateFrom
        );

        $data[self::RECOVERED_REVENUE] = $this->statisticsManagement->getAbandonmentRevenue(
            $storeIds,
            $dateTo,
            $dateFrom
        );

        $data[self::EMAILS_SENT] = $this->statisticsManagement->getTotalSend($storeIds, $dateTo, $dateFrom);
        $data[self::CARTS_RESTORED] = $this->statisticsManagement->getTotalRestoredCarts($storeIds, $dateTo, $dateFrom);
        $data[self::ORDERS_PLACED] = $this->statisticsManagement->getOrdersViaRestoredCarts($storeIds, $dateTo, $dateFrom);

        if ($data[self::EMAILS_SENT]) {
            $data[self::EFFICIENCY] =  $data[self::ORDERS_PLACED]  / $data[self::EMAILS_SENT] * self::PERCENT;
        } else {
            $data[self::EFFICIENCY] = 0;
        }

        $data[self::EFFICIENCY] = round($data[self::EFFICIENCY]) . '%';

        return $data;
    }

    /**
     * @param string $dateRange
     * @param string $dateFrom
     * @param string $dateTo
     *
     * @return array
     */
    private function getDate($dateRange, $dateFrom, $dateTo)
    {
        if ($dateRange === \Amasty\Acart\Model\Config\Source\DataRange::OVERALL) {
            $dateFrom = null;
            $dateTo = null;
        } elseif (!$dateRange == \Amasty\Acart\Model\Config\Source\DataRange::CUSTOM) {
            $dateFrom = $this->date->getDateWithOffsetByDays((-1) * ($dateRange - 1));
            $dateTo = $this->date->getDateWithOffsetByDays(1);
        } else {
            $dateFrom = $this->date->date('Y-m-d', $dateFrom);
            $dateTo = $this->date->date('Y-m-d', $dateTo);
        }

        return [$dateFrom, $dateTo];
    }

    /**
     * @return array
     */
    private function getParamsFromRequest()
    {
        return [
            $this->getRequest()->getParam(Filters::WEBSITE),
            $this->getRequest()->getParam(Filters::DATE_RANGE),
            $this->getRequest()->getParam(Filters::DATE_FROM),
            $this->getRequest()->getParam(Filters::DATE_TO)
        ];
    }

    /**
     * @param int $websiteId
     *
     * @return array
     */
    private function getStoreIdsByWebsiteId($websiteId)
    {
        $storeIds = [];
        $stores = $this->storeManager->getStores(true);

        /** @var \Magento\Store\Api\Data\StoreInterface $store */
        foreach ($stores as $store) {
            if ($websiteId === Filters::ALL || $websiteId === $store['website_id']) {
                $storeIds[] = $store->getId();
            }
        }

        return $storeIds;
    }

    /**
     * Determine if authorized to perform group action.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Amasty_Acart::acart_reports');
    }
}
