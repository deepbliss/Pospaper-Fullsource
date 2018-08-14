<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Service;

use Aheadworks\RewardPoints\Api\ProductShareManagementInterface;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Api\ProductShareRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\Service\ProductShareService
 */
class ProductShareService implements ProductShareManagementInterface
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var ProductShareRepositoryInterface
     */
    private $productShareRepository;

    /**
     * @var ProductShareInterfaceFactory
     */
    private $productShareFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param ProductShareRepositoryInterface $productShareRepository
     * @param ProductShareInterfaceFactory $productShareFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        ProductRepositoryInterface $productRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductShareRepositoryInterface $productShareRepository,
        ProductShareInterfaceFactory $productShareFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        $this->productShareRepository = $productShareRepository;
        $this->productShareFactory = $productShareFactory;
        $this->storeManager = $storeManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function add($customerId, $productId, $network)
    {
        $share = $this->productShareRepository->get($customerId, $productId, $network);
        if ($share->getId()) {
            throw new AlreadyExistsException(__('Product share already exists'));
        } else {
            $customer = $this->customerRepository->getById($customerId);
            $product = $this->productRepository->getById($productId);

            if ($customer->getId() && $product->getId()) {
                /** @var ProductShareInterface $productShare */
                $productShare = $this->productShareFactory->create();
                $productShare->setCustomerId($customerId);
                $productShare->setProductId($productId);
                $productShare->setNetwork($network);
                $productShare->setWebsiteId($this->storeManager->getStore()->getWebsiteId());

                if ($this->saveProductShare($productShare)) {
                    $this->customerRewardPointsService->addPointsForShares($customerId, $productId, $network);
                }
            } else {
                throw new CouldNotSaveException(__('Could save product share'));
            }
        }

        return true;
    }

    /**
     * @param ProductShareInterface $productShare
     * @throws CouldNotSaveException
     * @return boolean
     */
    private function saveProductShare(ProductShareInterface $productShare)
    {
        $result = false;
        try {
            $result = $this->productShareRepository->save($productShare);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $result;
    }
}
