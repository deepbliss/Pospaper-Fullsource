<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel;

use Aheadworks\RewardPoints\Model\EarnRate as EarnRateModel;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\DB\Adapter\DuplicateException;
use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\EarnRate
 */
class EarnRate extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init('aw_rp_earn_rate', 'rate_id');
    }

    /**
     * Save configuration values
     *
     * @param  array $configValue
     * @throws \Exception
     * @return \Aheadworks\RewardPoints\Model\ResourceModel\EarnRate
     */
    public function saveConfigValue($configValue)
    {
        if (is_array($configValue)) {
            $connection = $this->transactionManager->start($this->getConnection());
            try {
                $this->clear();
                foreach ($configValue as $configData) {
                    if (is_array($configData)) {
                        $bind = $this->prepareConfigData($configData);
                        $connection->insert($this->getMainTable(), $bind);
                    }
                }
                $this->transactionManager->commit();
            } catch (DuplicateException $e) {
                $this->transactionManager->rollBack();
                throw new AlreadyExistsException(
                    __('"Customer Lifetime Sales" values can\'t be the same for one group'),
                    $e
                );
            } catch (\Zend_Db_Statement_Exception $e) {
                $this->transactionManager->rollBack();
                if ($e->getCode() != 23000) {
                    throw $e;
                }
                throw new AlreadyExistsException(
                    __('"Customer Lifetime Sales" values can\'t be the same for one group'),
                    $e
                );
            } catch (\Exception $e) {
                $this->transactionManager->rollBack();
                throw $e;
            }
        }

        return $this;
    }

    /**
     * Prepare data for save configuration
     *
     * @param  array $configData
     * @return array
     */
    protected function prepareConfigData($configData)
    {
        $data = [];
        $connection = $this->getConnection();
        $fields = $connection->describeTable($this->getMainTable());
        foreach (array_keys($fields) as $field) {
            if (isset($configData[$field])) {
                $fieldValue = $configData[$field];
                if ($fieldValue instanceof \Zend_Db_Expr) {
                    $data[$field] = $fieldValue;
                } else {
                    if (null !== $fieldValue) {
                        $fieldValue = $this->_prepareTableValueForSave($fieldValue, $fields[$field]['DATA_TYPE']);
                        $data[$field] = $connection->prepareColumnValue($fields[$field], $fieldValue);
                    }
                }
            } elseif (! empty($fields[$field]['NULLABLE'])) {
                $data[$field] = null;
            }
        }
        return $data;
    }

    /**
     * Clear data
     *
     * @throws \Exception
     * @return \Aheadworks\RewardPoints\Model\ResourceModel\EarnRate
     */
    public function clear()
    {
        $connection = $this->transactionManager->start($this->getConnection());
        try {
            $connection->delete($this->getMainTable());
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }
        return $this;
    }

    /**
     * Get rate row id by customer group id,
     * lifetime sales amount and website id
     *
     * @param int $customerGroupId
     * @param int $lifetimeSalesAmount
     * @param int $websiteId
     * @return string|NULL
     */
    public function getRateRowId($customerGroupId, $lifetimeSalesAmount, $websiteId)
    {
        $select = $this->getRateRowSelect($customerGroupId, $lifetimeSalesAmount, $websiteId, 'rate_id');
        if ($select instanceof \Magento\Framework\DB\Select) {
            return $this->getConnection()->fetchOne($select);
        }
        return null;
    }

    /**
     * Get select query by customer group id,
     * lifetime sales amount and website id
     *
     * @param int $customerGroupId
     * @param int $lifetimeSalesAmount
     * @param int $websiteId
     * @param string $cols
     * @return \Magento\Framework\DB\Select
     */
    private function getRateRowSelect($customerGroupId, $lifetimeSalesAmount, $websiteId, $cols = '*')
    {
        $connection = $this->getConnection();

        if ($connection && $customerGroupId !== null && $websiteId !== null
        ) {
            $mainTable = $this->getMainTable();
            $select = $connection->select()->from($mainTable, $cols);

            $customerGroupIdField = $connection->quoteIdentifier(
                sprintf('%s.%s', $mainTable, EarnRateModel::CUSTOMER_GROUP_ID)
            );
            $websiteIdField = $connection->quoteIdentifier(
                sprintf('%s.%s', $mainTable, EarnRateModel::WEBSITE_ID)
            );
            $lifetimeSalesAmountField = $connection->quoteIdentifier(
                sprintf('%s.%s', $mainTable, EarnRateModel::LIFETIME_SALES_AMOUNT)
            );

            $select->where(
                '('.$customerGroupIdField.'=? OR '.$customerGroupIdField.'='.GroupInterface::CUST_GROUP_ALL.')',
                $customerGroupId
            );

            $select->where($websiteIdField . '=?', $websiteId);
            if (null !== $lifetimeSalesAmount) {
                $select->where($lifetimeSalesAmountField . '<=?', $lifetimeSalesAmount);
            }
            $select->order(EarnRateModel::LIFETIME_SALES_AMOUNT . ' DESC');
            $select->order(EarnRateModel::CUSTOMER_GROUP_ID . ' ASC');

            return $select;
        }
        return null;
    }
}
