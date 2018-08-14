<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api\Data;

/**
 * @api
 */
interface TransactionInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const TRANSACTION_ID = 'transaction_id';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const COMMENT_TO_CUSTOMER = 'comment_to_customer';
    const COMMENT_TO_CUSTOMER_PLACEHOLDER = 'comment_to_customer_placeholder';
    const COMMENT_TO_ADMIN = 'comment_to_admin';
    const BALANCE = 'balance';
    const CURRENT_BALANCE = 'current_balance';
    const TRANSACTION_DATE = 'transaction_date';
    const EXPIRATION_DATE = 'expiration_date';
    const WEBSITE_ID = 'website_id';
    const TYPE = 'type';
    const STATUS = 'status';
    const BALANCE_ADJUSTED = 'balance_adjusted';
    const ENTITIES = 'entities';
    const BALANCE_UPDATE_NOTIFIED = 'balance_update_notified';
    const EXPIRATION_NOTIFIED = 'expiration_notified';
    const CREATED_BY = 'created_by';
    /**#@-*/

    /**
     * Set transaction id
     *
     * @param  int $transactionId
     * @return TransactionInterface
     */
    public function setTransactionId($transactionId);

    /**
     * Get transaction id
     *
     * @return int
     */
    public function getTransactionId();

    /**
     * Set customer id
     *
     * @param  int $customerId
     * @return TransactionInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Set customer name
     *
     * @param  string $customerName
     * @return TransactionInterface
     */
    public function setCustomerName($customerName);

    /**
     * Get customer name
     *
     * @return string
     */
    public function getCustomerName();

    /**
     * Set customer email
     *
     * @param  string $customerEmail
     * @return TransactionInterface
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Set comment to customer
     *
     * @param  string $commentToCustomer
     * @return TransactionInterface
     */
    public function setCommentToCustomer($commentToCustomer);

    /**
     * Get comment to customer
     *
     * @return string
     */
    public function getCommentToCustomer();

    /**
     * Set placeholder comment to customer
     *
     * @param  string $commentToCustomerPlaceholder
     * @return TransactionInterface
     */
    public function setCommentToCustomerPlaceholder($commentToCustomerPlaceholder);

    /**
     * Get placeholder comment to customer
     *
     * @return string
     */
    public function getCommentToCustomerPlaceholder();

    /**
     * Set comment to admin
     *
     * @param  string $commentToAdmin
     * @return TransactionInterface
     */
    public function setCommentToAdmin($commentToAdmin);

    /**
     * Get comment to admin
     *
     * @return string
     */
    public function getCommentToAdmin();

    /**
     * Set balance
     *
     * @param  int $balance
     * @return TransactionInterface
     */
    public function setBalance($balance);

    /**
     * Get balance
     *
     * @return int
     */
    public function getBalance();

    /**
     * Set current balance
     *
     * @param  float $balance
     * @return TransactionInterface
     */
    public function setCurrentBalance($balance);

    /**
     * Get current balance
     *
     * @return float
     */
    public function getCurrentBalance();

    /**
     * Set transaction date
     *
     * @param string $transactionDate
     * @return TransactionInterface
     */
    public function setTransactionDate($transactionDate);

    /**
     * Get transaction date
     *
     * @return string
     */
    public function getTransactionDate();

    /**
     * Set expiration date
     *
     * @param  string $expirationDate
     * @return TransactionInterface
     */
    public function setExpirationDate($expirationDate);

    /**
     * Get expiration date
     *
     * @return string
     */
    public function getExpirationDate();

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return TransactionInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();

    /**
     * Set type
     *
     * @param int $type
     * @return TransactionInterface
     */
    public function setType($type);

    /**
     * Get type
     *
     * @return int
     */
    public function getType();

    /**
     * Set status
     *
     * @param int $status
     * @return TransactionInterface
     */
    public function setStatus($status);

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Set balance adjusted
     *
     * @param int $balanceAdjusted
     * @return TransactionInterface
     */
    public function setBalanceAdjusted($balanceAdjusted);

    /**
     * Get balance adjusted
     *
     * @return int
     */
    public function getBalanceAdjusted();

    /**
     * Set transaction entities
     *
     * @param array $entities
     * @return TransactionInterface
     */
    public function setEntities($entities);

    /**
     * Get transaction entities
     *
     * @return int
     */
    public function getEntities();

    /**
     * Set balance update notified
     *
     * @param int $balanceUpdateNotified
     * @return TransactionInterface
     */
    public function setBalanceUpdateNotified($balanceUpdateNotified);

    /**
     * Get balance update notified
     *
     * @return int
     */
    public function getBalanceUpdateNotified();

    /**
     * Set expiration notified
     *
     * @param int $expirationNotified
     * @return TransactionInterface
     */
    public function setExpirationNotified($expirationNotified);

    /**
     * Get expiration notified
     *
     * @return int
     */
    public function getExpirationNotified();

    /**
     * Set created by
     *
     * @param int $createdBy
     * @return TransactionInterface
     */
    public function setCreatedBy($createdBy);

    /**
     * Get created by
     *
     * @return int
     */
    public function getCreatedBy();
}
