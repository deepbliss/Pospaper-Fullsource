<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Unsubscribe;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Aheadworks\RewardPoints\Model\KeyEncryptor;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;
use Magento\Framework\DataObject;

/**
 * Class Index
 *
 * @package Aheadworks\RewardPoints\Controller\Unsubscribe
 */
class Index extends Action
{
    /**
     * @var PointsSummaryService
     */
    private $pointsSummaryService;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @var KeyEncryptor
     */
    private $keyEncryptor;

    /**
     * @param Context $context
     * @param PointsSummaryService $pointsSummaryService
     * @param DataObject $dataObject
     * @param KeyEncryptor $keyEncryptor
     */
    public function __construct(
        Context $context,
        PointsSummaryService $pointsSummaryService,
        DataObject $dataObject,
        KeyEncryptor $keyEncryptor
    ) {
        $this->pointsSummaryService = $pointsSummaryService;
        $this->dataObject = $dataObject;
        $this->keyEncryptor = $keyEncryptor;
        parent::__construct($context);
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        try {
            $unsubscribeData = $this->keyEncryptor->decrypt($this->getRequest()->getParam('key'));

            if (isset($unsubscribeData['customer_id'], $unsubscribeData['website_id'])) {
                $summaryData = $this->dataObject->setData(
                    [
                        PointsSummaryInterface::CUSTOMER_ID => $unsubscribeData['customer_id'],
                        PointsSummaryInterface::WEBSITE_ID => $unsubscribeData['website_id'],
                        PointsSummaryInterface::BALANCE_UPDATE_NOTIFICATION_STATUS => SubscribeStatus::UNSUBSCRIBED,
                        PointsSummaryInterface::EXPIRATION_NOTIFICATION_STATUS => SubscribeStatus::UNSUBSCRIBED
                    ]
                );
                $this->pointsSummaryService->updateCustomerSummary($summaryData);
                $this->messageManager->addSuccessMessage(__('Your Reward Points subscription settings were updated.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving your Reward Points subscription.')
            );
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }
}
