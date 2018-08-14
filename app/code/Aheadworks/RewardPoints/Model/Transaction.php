<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction as TransactionResource;

/**
 * Class Aheadworks\RewardPoints\Model\Transaction
 */
class Transaction extends \Magento\Framework\Model\AbstractModel implements TransactionInterface
{
    //@codeCoverageIgnoreStart

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(TransactionResource::class);
    }

    /**
     * {@inheritDoc}
     */
    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionId()
    {
        return $this->getData(self::TRANSACTION_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * {@inheritDoc}
     */
    public function setCustomerName($customerName)
    {
        return $this->setData(self::CUSTOMER_NAME, $customerName);
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerName()
    {
        return $this->getData(self::CUSTOMER_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setCommentToCustomer($commentToCustomer)
    {
        return $this->setData(self::COMMENT_TO_CUSTOMER, $commentToCustomer);
    }

    /**
     * {@inheritDoc}
     */
    public function getCommentToCustomer()
    {
        return $this->getData(self::COMMENT_TO_CUSTOMER);
    }

    /**
     * {@inheritDoc}
     */
    public function setCommentToCustomerPlaceholder($commentToCustomerPlaceholder)
    {
        return $this->setData(self::COMMENT_TO_CUSTOMER_PLACEHOLDER, $commentToCustomerPlaceholder);
    }

    /**
     * {@inheritDoc}
     */
    public function getCommentToCustomerPlaceholder()
    {
        return $this->getData(self::COMMENT_TO_CUSTOMER_PLACEHOLDER);
    }

    /**
     * {@inheritDoc}
     */
    public function setCommentToAdmin($commentToAdmin)
    {
        return $this->setData(self::COMMENT_TO_ADMIN, $commentToAdmin);
    }

    /**
     * {@inheritDoc}
     */
    public function getCommentToAdmin()
    {
        return $this->getData(self::COMMENT_TO_ADMIN);
    }

    /**
     * {@inheritDoc}
     */
    public function setBalance($balance)
    {
        return $this->setData(self::BALANCE, $balance);
    }

    /**
     * {@inheritDoc}
     */
    public function getBalance()
    {
        return $this->getData(self::BALANCE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentBalance($balance)
    {
        return $this->setData(self::CURRENT_BALANCE, $balance);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentBalance()
    {
        return $this->getData(self::CURRENT_BALANCE);
    }

    /**
     * {@inheritDoc}
     */
    public function setTransactionDate($transactionDate)
    {
        return $this->setData(self::TRANSACTION_DATE, $transactionDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionDate()
    {
        return $this->getData(self::TRANSACTION_DATE);
    }

    /**
     * {@inheritDoc}
     */
    public function setExpirationDate($expirationDate)
    {
        return $this->setData(self::EXPIRATION_DATE, $expirationDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getExpirationDate()
    {
        return $this->getData(self::EXPIRATION_DATE);
    }

    /**
     * {@inheritDoc}
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * {@inheritDoc}
     */
    public function getWebsiteId()
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * {@inheritDoc}
     */
    public function setBalanceAdjusted($balanceAdjusted)
    {
        return $this->setData(self::BALANCE_ADJUSTED, $balanceAdjusted);
    }

    /**
     * {@inheritDoc}
     */
    public function getBalanceAdjusted()
    {
        return $this->getData(self::BALANCE_ADJUSTED);
    }

    /**
     * {@inheritDoc}
     */
    public function setEntities($entities)
    {
        return $this->setData(self::ENTITIES, $entities);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntities()
    {
        return $this->getData(self::ENTITIES);
    }

    /**
     * {@inheritDoc}
     */
    public function setBalanceUpdateNotified($balanceUpdateNotified)
    {
        return $this->setData(self::BALANCE_UPDATE_NOTIFIED, $balanceUpdateNotified);
    }

    /**
     * {@inheritDoc}
     */
    public function getBalanceUpdateNotified()
    {
        return $this->getData(self::BALANCE_UPDATE_NOTIFIED);
    }

    /**
     * {@inheritDoc}
     */
    public function setExpirationNotified($expirationNotified)
    {
        return $this->setData(self::EXPIRATION_NOTIFIED, $expirationNotified);
    }

    /**
     * {@inheritDoc}
     */
    public function getExpirationNotified()
    {
        return $this->getData(self::EXPIRATION_NOTIFIED);
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedBy($createdBy)
    {
        return $this->setData(self::CREATED_BY, $createdBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedBy()
    {
        return $this->getData(self::CREATED_BY);
    }
}
