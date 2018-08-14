<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Service;

use Aheadworks\RewardPoints\Api\RewardPointsCartManagementInterface;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Api\CustomAttributesDataInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Aheadworks\RewardPoints\Model\Service$RewardPointsCartService
 */
class RewardPointsCartService implements RewardPointsCartManagementInterface
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param CartRepositoryInterface $quoteRepository
     * @param Config $config
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        CartRepositoryInterface $quoteRepository,
        Config $config
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->quoteRepository = $quoteRepository;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }
        return $quote->getAwUseRewardPoints();
    }

    /**
     * {@inheritDoc}
     */
    public function set($cartId)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }

        $onceMinBalance = $this->customerRewardPointsService->getCustomerRewardPointsOnceMinBalance(
            $quote->getCustomerId(),
            $quote->getStore()->getWebsiteId()
        );
        if (!$quote->getCustomerId()
            || !$this->customerRewardPointsService->getCustomerRewardPointsBalance($quote->getCustomerId())
            || $onceMinBalance
        ) {
            throw new NoSuchEntityException(__('No reward points to be used'));
        }

        $quote->getShippingAddress()->setCollectShippingRates(true);
        try {
            $quote->setAwUseRewardPoints(true);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not apply reward points'));
        }

        if (!$quote->getAwUseRewardPoints()) {
            throw new NoSuchEntityException(__('No possibility to use reward points discounts in the cart'));
        }

        return [
            CustomAttributesDataInterface::CUSTOM_ATTRIBUTES => [
                'success' => true,
                'message' => $this->getMessage($quote)
            ]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function remove($cartId)
    {
        /** @var  \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }

        $quote->getShippingAddress()->setCollectShippingRates(true);
        try {
            $quote->setAwUseRewardPoints(false);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not remove reward points'));
        }
        return true;
    }

    /**
     * Retrieves message to show customer
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return string
     */
    private function getMessage($quote)
    {
        $shareCoveredValue = $this->config->getShareCoveredValue($quote->getStore()->getWebsiteId());
        if ($shareCoveredValue && ($shareCoveredValue != 100)) {
            $message = __(
                'Reward points were successfully applied. '
                . 'Important: It is allowed to cover only %1% of the purchase with Reward Points.',
                $shareCoveredValue
            );
        } else {
            $message = __('Reward points were successfully applied.');
        }
        return $message;
    }
}
