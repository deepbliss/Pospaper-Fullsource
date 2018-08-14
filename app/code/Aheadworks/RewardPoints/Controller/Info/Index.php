<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Info;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Class Aheadworks\RewardPoints\Controller\Info\Index
 */
class Index extends Action
{
    /**
     * Customer session model
     *
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerRewardPointsManagementInterface $customerRewardPointsService
    ) {
        $this->customerSession = $customerSession;
        $this->customerRewardPointsService = $customerRewardPointsService;
        parent::__construct($context);
    }

    /**
     *  {@inheritDoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('aw_rewardpoints/info');
        }
        if ($block = $resultPage->getLayout()->getBlock('rewardpoints_customer_rewardpoints')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $resultPage->getConfig()->getTitle()->set(__('Reward Points'));
        return $resultPage;
    }

    /**
     * Check customer authentication for some actions
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     * @throws NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->customerSession->authenticate()) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }
        $customerRewardPointsSpendRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsSpendRateByGroup($this->customerSession->getId());
        $customerRewardPointsEarnRateByGroup = $this->customerRewardPointsService
            ->isCustomerRewardPointsEarnRateByGroup($this->customerSession->getId());

        if ($customerRewardPointsSpendRateByGroup || $customerRewardPointsEarnRateByGroup) {
        } else {
            throw new NotFoundException(__('Page not found.'));
        }
        return parent::dispatch($request);
    }
}
