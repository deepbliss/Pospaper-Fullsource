<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Transactions;

use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\TransactionRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_RewardPoints::aw_reward_points_transaction_save';

    /**
     * @var PostDataProcessor
     */
    private $dataProcessor;

    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param TransactionRepositoryInterface $transactionRepository
     * @param JsonFactory $jsonFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        TransactionRepositoryInterface $transactionRepository,
        JsonFactory $jsonFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->transactionRepository = $transactionRepository;
        $this->jsonFactory = $jsonFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     *  {@inheritDoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $id) {
            try {
                /** @var TransactionInterface $transaction **/
                $transaction = $this->transactionRepository->getById($id);
                $postData = $postItems[$id];
                $postData['balance'] = $transaction->getBalance();
                $transactionData = $this->filterPost($postData);
                $this->validatePost($transactionData, $transaction, $error, $messages);
                if (!$error) {
                    $this->setTransactionData($transaction, $transactionData);
                    $this->transactionRepository->save($transaction);
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithId($transaction, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithId($transaction, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithId(
                    $transaction,
                    __('Something went wrong while saving the transaction.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Filter post data
     *
     * @param array $postData
     * @return array
     */
    private function filterPost($postData = [])
    {
        $filterData = $this->dataProcessor->filter($postData);
        return $filterData;
    }

    /**
     * Validate post data
     *
     * @param array $transactionData
     * @param TransactionInterface $transaction
     * @param boolean $error
     * @param array $messages
     * @return void
     */
    private function validatePost(
        array $transactionData,
        TransactionInterface $transaction,
        &$error,
        array &$messages
    ) {
        if (!($this->dataProcessor->validate($transactionData, $transaction)
            && $this->dataProcessor->validateRequireEntry($transactionData))
        ) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithId($transaction, $error->getText());
            }
        }
    }

    /**
     * Retrieve error message with transaction id
     *
     * @param TransactionInterface $transaction
     * @param string $errorText
     * @return string
     */
    private function getErrorWithId(TransactionInterface $transaction, $errorText)
    {
        return '[Transaction ID: ' . $transaction->getTransactionId() . '] ' . $errorText;
    }

    /**
     * Set transaction data
     *
     * @param TransactionInterface $transaction
     * @param array $transactionData
     * @return \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit
     */
    private function setTransactionData(
        TransactionInterface $transaction,
        array $transactionData
    ) {
        $this->dataObjectHelper->populateWithArray(
            $transaction,
            $transactionData,
            TransactionInterface::class
        );
        return $this;
    }
}
