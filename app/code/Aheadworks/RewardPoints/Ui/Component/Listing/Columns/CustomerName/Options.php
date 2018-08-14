<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\Component\Listing\Columns\CustomerName;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Aheadworks\RewardPoints\Ui\Component\Listing\Columns\CustomerName\Options
 */
class Options implements OptionSourceInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

     /**
      *  {@inheritDoc}
      */
    public function toOptionArray()
    {
        $customersOptions = [];
        /** @var CustomerInterface[] $customers */
        $customers = $this->customerRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($customers->getItems() as $customer) {
            if ($customer->getId() == 0) {
                continue;
            }
            $customersOptions[] = [
                'label' => $this->compileCustomerName($customer),
                'value' => $customer->getId(),
            ];
        }

        return $customersOptions;
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
