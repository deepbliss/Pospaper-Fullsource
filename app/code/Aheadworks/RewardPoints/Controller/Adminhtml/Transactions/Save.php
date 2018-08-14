<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Transactions;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\Save
 */
class Save extends \Magento\Backend\App\Action
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
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        DataObjectHelper $dataObjectHelper
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     *  {@inheritDoc}
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {
                $data = $this->prepareData($data);
                $this->dataPersistor->set('transaction', $data);
                $data = $this->dataProcessor->filter($data);
                $this->processSave($data);
                $this->dataPersistor->clear('transaction');
                $this->messageManager->addSuccessMessage(__('You saved the transactions.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the transaction.')
                );
            }
            return $resultRedirect->setPath('*/*/new');
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process save transaction
     *
     * @param array $data
     * @throws LocalizedException
     * @return void
     */
    private function processSave(array $data)
    {
        $customerSelection = $this->dataProcessor->customerSelectionFilter($data);

        if (!empty($customerSelection)) {
            foreach ($customerSelection as $transactionData) {
                $this->customerRewardPointsService->resetCustomer();
                $this->customerRewardPointsService->saveAdminTransaction($transactionData);
            }
        } else {
            throw new LocalizedException(
                __('Please select customers or confirm that they belong to the website of the current transaction')
            );
        }
    }

    /**
     * Prepare form data
     *
     * @param array $data
     * @return array
     */
    private function prepareData($data)
    {
        if (isset($data['customer_selections'])) {
            $data['customer_selections'] = json_decode($data['customer_selections'], true);
        }
        return $data;
    }
}
