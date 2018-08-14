<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Transactions;

use Aheadworks\RewardPoints\Model\Source\Transaction\Expire;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Message\ManagerInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;

/**
 * Class Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\PostDataProcessor
 */
class PostDataProcessor
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const POST_DATA_BALANCE = 'balance';
    const POST_DATA_COMMENT_TO_CUSTOMER = 'comment_to_customer';
    const POST_DATA_COMMENT_TO_ADMIN = 'comment_to_admin';
    const POST_DATA_TRANSACTION_DATE = 'transaction_date';
    const POST_DATA_EXPIRATION_DATE = 'expiration_date';
    const POST_DATA_EXPIRE = 'expire';
    const POST_DATA_EXPIRE_IN_DAYS = 'expire_in_days';
    const POST_DATA_CUSTOMER_SELECTIONS = 'customer_selections';
    /**#@-*/

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var array
     */
    private $filterRules = [
         self::POST_DATA_COMMENT_TO_CUSTOMER => 'stripTags',
         self::POST_DATA_COMMENT_TO_ADMIN => 'stripTags',
    ];

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @param FilterManager $filterManager
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        FilterManager $filterManager,
        ManagerInterface $messageManager
    ) {
        $this->filterManager = $filterManager;
        $this->messageManager = $messageManager;
    }

    /**
     * Filter post data
     *
     * @param array $data
     * @return array
     */
    public function filter(array $data)
    {
        $filterRules = [];

        foreach ($this->filterRules as $dateField => $filter) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->filterManager->get($filter);
            }
        }

        if (isset($data[self::POST_DATA_EXPIRATION_DATE])) {
            $this->addExpireFilter($filterRules, $data);
        }

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    /**
     * Filter customer selection data for create transaction
     *
     * @param array $data
     * @return array
     */
    public function customerSelectionFilter($data)
    {
        return $this->filterManager->custselect($data);
    }

    /**
     * Add filter for expire fields
     *
     * @param array $filterRules
     * @param array $data
     * @return void
     */
    private function addExpireFilter(&$filterRules, &$data)
    {
        $filterConfig = [];

        if (isset($data[self::POST_DATA_EXPIRE])) {
            if (isset($data[self::POST_DATA_EXPIRE_IN_DAYS])) {
                $filterConfig = [
                    self::POST_DATA_EXPIRE => $data[self::POST_DATA_EXPIRE],
                    self::POST_DATA_EXPIRE_IN_DAYS => $data[self::POST_DATA_EXPIRE_IN_DAYS],
                ];
                unset($data[self::POST_DATA_EXPIRE_IN_DAYS]);
            }
            unset($data[self::POST_DATA_EXPIRE]);
        }

        if ((int)$data[self::POST_DATA_BALANCE] > 0) {
            $filterRules[self::POST_DATA_EXPIRATION_DATE] = $this->filterManager->get(
                'expdate',
                ['config' => $filterConfig]
            );
        } else {
            $data[self::POST_DATA_EXPIRATION_DATE] = null;
        }
    }

    /**
     * Validate data
     *
     * @param array $data
     * @param TransactionInterface $transaction
     * @return boolean
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($data, $transaction)
    {
        $errorNo = true;
        if ($transaction->getStatus() != Status::ACTIVE) {
            $errorNo = false;
            $this->messageManager->addErrorMessage(
                __('You can not save the transaction. The transaction is not active.')
            );
        }
        return $errorNo;
    }

    /**
     * Validate require data
     *
     * @param array $data
     * @return boolean
     */
    public function validateRequireEntry(array $data)
    {
        $requiredFields = [
            'balance' => __('Points balance adjustment'),
            'customer_selections' => __('Customers'),
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields))
                && ((is_array($value) ? count($value) == 0 : $value == ''))) {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }
}
