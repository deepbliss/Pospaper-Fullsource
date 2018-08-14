<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\Component\Listing\Columns;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Aheadworks\RewardPoints\Ui\Component\Listing\Columns\CustomerName
 */
class CustomerName extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var array
     */
    private $customerNameCache = [];

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param CustomerRepositoryInterface $customerRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        CustomerRepositoryInterface $customerRepository,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     *  {@inheritDoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']) && is_array($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['customer_id'])) {
                    $customerName = isset($item[$this->getName()])
                        ? $item[$this->getName()]
                        : $this->getCustomerName($item['customer_id']);

                    $item[$this->getName()] = [
                        'url' => $this->getCustomerLinkUrl($item['customer_id']),
                        'text' => $customerName,
                    ];
                }
            }
        }
        return $dataSource;
    }

    /**
     * Retrieve link to customer edit page
     *
     * @param int $customerId
     * @return string
     */
    private function getCustomerLinkUrl($customerId)
    {
        return $this->urlBuilder->getUrl('customer/index/edit', ['id' => $customerId]);
    }

    /**
     * Retrieve customer name
     *
     * @param int $customerId
     * @return string
     */
    private function getCustomerName($customerId)
    {
        if (isset($this->customerNameCache[$customerId])) {
            return $this->customerNameCache[$customerId];
        }
        $customer = $this->customerRepository->getById($customerId);
        $customerName = $this->compileCustomerName($customer);

        $this->customerNameCache[$customerId] = $customerName;
        return $customerName;
    }

    /**
     * Compile customer name from firstname and lastname
     *
     * @param CustomerInterface $customer
     * @return string
     */
    private function compileCustomerName(CustomerInterface $customer)
    {
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }
}
