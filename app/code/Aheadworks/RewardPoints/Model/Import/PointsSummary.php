<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Import;

use Magento\Framework\UrlInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class PointsSummary
 *
 * @package Aheadworks\RewardPoints\Model\Import
 */
class PointsSummary extends AbstractImport
{
    /**#@+
     * Constants for fields, that are present in the import data,
     * but are missing in PointsSummaryInterface
     */
    const CUSTOMER_NAME_FIELD_NAME = 'customer_name';
    const CUSTOMER_EMAIL_FIELD_NAME = 'customer_email';
    const LIFETIME_SALES_FIELD_NAME = 'lifetime_sales';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    protected $namespace = 'aw_reward_points_customers_listing';

    /**
     * {@inheritdoc}
     */
    protected $logFileName = 'aw_rp_points_summary_import';

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param Filter $filter
     * @param RequestInterface $request
     * @param UrlInterface $url
     * @param Logger $logger
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        Filter $filter,
        RequestInterface $request,
        UrlInterface $url,
        Logger $logger,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($dataObjectHelper, $filter, $request, $url, $logger);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function convertDataToObject($filteredRows)
    {
        $pointsSummaryList = [];
        foreach ($filteredRows as $row) {
            $row = $this->getRowData($row);
            $preparedData = $this->preparePointsSummaryData($row);
            if ($this->isPointsSummaryDataValid($preparedData)) {
                $pointsSummaryList[] = $preparedData;
            }
        }
        return $pointsSummaryList;
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaderFields()
    {
        return [
            [
                'header' => __('Customer Name'),
                'field_name' => self::CUSTOMER_NAME_FIELD_NAME
            ],
            [
                'header' => __('Customer Email'),
                'field_name' => self::CUSTOMER_EMAIL_FIELD_NAME,
                'required' => true
            ],
            [
                'header' => __('Lifetime sales'),
                'field_name' => self::LIFETIME_SALES_FIELD_NAME
            ],
            [
                'header' => __('Current customer balance'),
                'field_name' => PointsSummaryInterface::POINTS,
                'required' => true
            ],
            [
                'header' => __('Total points earned'),
                'field_name' => PointsSummaryInterface::POINTS_EARN
            ],
            [
                'header' => __('Total points spent'),
                'field_name' => PointsSummaryInterface::POINTS_SPEND
            ],
            [
                'header' => __('Website'),
                'field_name' => PointsSummaryInterface::WEBSITE_ID,
                'required' => true
            ],
            [
                'header' => __('Balance Update Notifications (status)'),
                'field_name' => PointsSummaryInterface::BALANCE_UPDATE_NOTIFICATION_STATUS
            ],
            [
                'header' => __('Points Expiration Notification (status)'),
                'field_name' => PointsSummaryInterface::EXPIRATION_NOTIFICATION_STATUS
            ]
        ];
    }

    /**
     * Prepare points summary data with additional fields for import
     *
     * @param array $row
     * @return array
     */
    private function preparePointsSummaryData($row)
    {
        $row[PointsSummaryInterface::CUSTOMER_ID] = null;
        if (isset($row[PointsSummaryInterface::WEBSITE_ID])
            && isset($row[self::CUSTOMER_EMAIL_FIELD_NAME])
        ) {
            $this->searchCriteriaBuilder->addFilter(
                CustomerInterface::EMAIL,
                $row[self::CUSTOMER_EMAIL_FIELD_NAME]
            );
            $this->searchCriteriaBuilder->addFilter(
                CustomerInterface::WEBSITE_ID,
                $row[PointsSummaryInterface::WEBSITE_ID]
            );
            $customers = $this->customerRepository->getList($this->searchCriteriaBuilder->create())->getItems();
            if (!empty($customers)) {
                $customer = array_shift($customers);
                $customerId = $customer->getId();
                $row[PointsSummaryInterface::CUSTOMER_ID] = $customerId;
            }
        }
        if (empty($row[PointsSummaryInterface::CUSTOMER_ID])) {
            $this->addMessages([
                __('Customer with email %1 wasn\'t found', $row[self::CUSTOMER_EMAIL_FIELD_NAME])
            ]);
        }

        return $row;
    }

    /**
     * Check if prepared points summary data valid
     *
     * @param array $row
     * @return bool
     */
    private function isPointsSummaryDataValid($row)
    {
        $isValid = true;
        $requiredFields = $this->getRequiredForImportFieldNames();
        foreach ($requiredFields as $fieldName) {
            if (!isset($row[$fieldName])) {
                $this->addMessages([
                    __('Required field %1 is missing', $fieldName)
                ]);
                $isValid = false;
                break;
            }
        }
        if (!$this->isPointsBalanceValid($row[PointsSummaryInterface::POINTS])) {
            $isValid = false;
            $this->addMessages([
                __('Points balance is invalid for customer with email %1', $row[self::CUSTOMER_EMAIL_FIELD_NAME])
            ]);
        }
        return $isValid;
    }

    /**
     * Retrieves names of fields, that required for import
     *
     * @return array
     */
    private function getRequiredForImportFieldNames()
    {
        return [
            PointsSummaryInterface::WEBSITE_ID,
            PointsSummaryInterface::CUSTOMER_ID,
            PointsSummaryInterface::POINTS
        ];
    }

    /**
     * Check if imported points balance valid
     *
     * @param mixed $pointsBalance
     * @return bool
     */
    private function isPointsBalanceValid($pointsBalance)
    {
        return is_numeric($pointsBalance);
    }
}
