<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Filters\Transaction;

use Aheadworks\RewardPoints\Api\Data\TransactionInterface;

/**
 * Class Aheadworks\RewardPoints\Model\Filters\Transaction\CustomerSelection
 */
class CustomerSelection implements \Zend_Filter_Interface
{
    /**#@+
     * Constant for default field name for customer selection
     */
    const DEFAULT_FIELD_NAME = 'customer_selections';
    /**#@-*/

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName = null)
    {
        if ($fieldName == null) {
            $this->fieldName = self::DEFAULT_FIELD_NAME;
        } else {
            $this->fieldName = $fieldName;
        }
    }

    /**
     *  {@inheritDoc}
     */
    public function filter($value)
    {
        $result = [];
        if (is_array($value)
            && isset($value[$this->fieldName])
            && is_array($value[$this->fieldName])
        ) {
            foreach ($value[$this->fieldName] as $customerSelection) {
                if ($this->isCustomerDataValid($value, $customerSelection)) {
                    $result[] = [
                        TransactionInterface::CUSTOMER_ID => $this->get(
                            $customerSelection,
                            TransactionInterface::CUSTOMER_ID
                        ),
                        TransactionInterface::CUSTOMER_NAME => $this->get(
                            $customerSelection,
                            TransactionInterface::CUSTOMER_NAME
                        ),
                        TransactionInterface::CUSTOMER_EMAIL => $this->get(
                            $customerSelection,
                            TransactionInterface::CUSTOMER_EMAIL
                        ),
                        TransactionInterface::COMMENT_TO_CUSTOMER => $this->get(
                            $value,
                            TransactionInterface::COMMENT_TO_CUSTOMER
                        ),
                        TransactionInterface::COMMENT_TO_ADMIN => $this->get(
                            $value,
                            TransactionInterface::COMMENT_TO_ADMIN
                        ),
                        TransactionInterface::BALANCE => $this->get(
                            $value,
                            TransactionInterface::BALANCE
                        ),
                        TransactionInterface::EXPIRATION_DATE => $this->get(
                            $value,
                            TransactionInterface::EXPIRATION_DATE
                        ),
                        TransactionInterface::WEBSITE_ID => $this->get(
                            $value,
                            TransactionInterface::WEBSITE_ID
                        ),

                    ];
                }
            }
        }
        return $result;
    }

    /**
     * Checks is need to add customer selection data to result
     *
     * @param array $value
     * @param array $customerData
     * @return bool
     */
    private function isCustomerDataValid($value, $customerData)
    {
        $result = true;
        $websiteId = $this->get($value, TransactionInterface::WEBSITE_ID);
        $customerWebsiteId = $this->get($customerData, TransactionInterface::WEBSITE_ID);
        if (is_array($customerWebsiteId)) {
            $customerWebsiteId = $customerWebsiteId[0];
        }
        if ($customerWebsiteId != $websiteId) {
            $result = false;
        }
        return $result;
    }

    /**
     * Get data from array
     *
     * @param array $data
     * @param string $field
     * @return string
     */
    private function get($data, $field)
    {
        return (is_array($data) && isset($data[$field])) ? $data[$field] : null;
    }
}
