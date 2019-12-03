<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model\Service;

use Magento\Store\Model\Store as StoreModel;

/**
 * IntervalDuplicator Class
 *
 * Separated from IntervalManager because there's almost no commonality and this particular aspect is a beast.
 */
class IntervalDuplicator
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface
     */
    protected $intervalRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface
     */
    protected $intervalManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory
     */
    protected $optionCollectionFactory;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * IntervalManager constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->intervalRepository = $intervalRepository;
        $this->helper = $helper;
        $this->intervalManager = $intervalManager;
        $this->productRepository = $productRepository;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->eventManager = $eventManager;
    }

    /**
     * Duplicate intervals for the given duplicated product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $newProduct
     * @return IntervalDuplicator
     */
    public function duplicateProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $newProduct)
    {
        /** @var \Magento\Catalog\Model\Product $newProduct */

        // Map product IDs
        $oldProductId = !empty($newProduct->getData('original_link_id'))
            ? $newProduct->getData('original_link_id')
            : $newProduct->getData('original_id');
        $newProductId = $newProduct->getData('row_id') ?: $newProduct->getId();

        // Ensure the product WAS duplicated and we have all the necessary data.
        if (!$newProduct->getData('is_duplicate')
            || (int)$newProduct->getData('subscription_active') !== 1
            || empty($oldProductId)
            || $newProduct->getId() === null) {
            return $this;
        }

        try {
            // Load original product and map options
            $oldProduct = $this->productRepository->getById($oldProductId);

            $oldOption = $this->intervalManager->getSubscriptionOption($oldProduct);
            $newOption = $this->getNewSubscriptionOption($newProduct, $oldOption);

            if ($oldOption === null || $newOption === null) {
                return $this;
            }

            // Map value IDs
            $oldOptionLabelsById = [];
            foreach ($oldOption->getValues() as $value) {
                $oldOptionLabelsById[$value->getOptionTypeId()] = $value->getTitle();
            }

            $newOptionIdsByLabel = [];
            foreach ($newOption->getValues() as $value) {
                $newOptionIdsByLabel[$value->getTitle()] = $value->getOptionTypeId();
            }

            // Load old intervals, duplicate each, update IDs, and save for the new product.
            $oldIntervals = $this->intervalRepository->getIntervalsByProductId($oldProductId);
            foreach ($oldIntervals->getItems() as $oldInterval) {
                try {
                    $this->duplicateInterval(
                        $oldProductId,
                        $newProductId,
                        $oldInterval,
                        $newOption,
                        $oldOptionLabelsById,
                        $newOptionIdsByLabel
                    );
                } catch (\Magento\Framework\Exception\NotFoundException $e) {
                    $this->helper->log('subscriptions', $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->helper->log('subscriptions', $e->getMessage());
        }

        return $this;
    }

    /**
     * Get option from new product matching $oldOption.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $newProduct
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null $oldOption
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null
     */
    protected function getNewSubscriptionOption(
        \Magento\Catalog\Api\Data\ProductInterface $newProduct,
        $oldOption
    ) {
        if ($oldOption !== null) {
            $optionCollection = $this->optionCollectionFactory->create();
            $optionCollection->addTitleToResult(StoreModel::DEFAULT_STORE_ID);
            $optionCollection->addFieldToFilter('product_id', $newProduct->getData('row_id') ?: $newProduct->getId());
            $optionCollection->addValuesToResult(StoreModel::DEFAULT_STORE_ID);

            foreach ($optionCollection as $newOption) {
                if ($newOption->getTitle() === $oldOption->getTitle()) {
                    return $newOption;
                }
            }
        }

        return null;
    }

    /**
     * Create a new interval given an existing interval and values to change it to.
     *
     * Too many parameters, but still better than a monolithic method.
     *
     * @param int $oldProductId
     * @param int $newProductId
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $oldInterval
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $newOption
     * @param string[] $oldOptionLabelsById
     * @param int[] $newOptionIdsByLabel
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    protected function duplicateInterval(
        $oldProductId,
        $newProductId,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $oldInterval,
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $newOption,
        $oldOptionLabelsById,
        $newOptionIdsByLabel
    ) {
        $newInterval = clone $oldInterval;
        $newInterval->setId(null);
        $newInterval->setProductId($newProductId);
        $newInterval->setOptionId($newOption->getOptionId());

        // If we can't find a match by label, skip it.
        if (isset($oldOptionLabelsById[ $oldInterval->getValueId() ])) {
            $oldLabel = $oldOptionLabelsById[ $oldInterval->getValueId() ];

            if (isset($newOptionIdsByLabel[ $oldLabel ])) {
                $newInterval->setValueId($newOptionIdsByLabel[ $oldLabel ]);

                // We've updated all the IDs--save it. Fire off events in case we have any dependencies.
                $this->eventManager->dispatch(
                    'paradoxlabs_subscription_interval_duplicate_before',
                    [
                        'old_interval' => $oldInterval,
                        'new_interval' => $newInterval,
                        'new_product_option' => $newOption,
                    ]
                );

                $this->intervalRepository->save($newInterval);

                $this->eventManager->dispatch(
                    'paradoxlabs_subscription_interval_duplicate_after',
                    [
                        'old_interval' => $oldInterval,
                        'new_interval' => $newInterval,
                        'new_product_option' => $newOption,
                    ]
                );

                return $newInterval;
            }

            throw new \Magento\Framework\Exception\NotFoundException(
                __(
                    'Could not find new value matching label "%1" when duplicating product %2; skipping.',
                    $oldLabel,
                    $oldProductId
                )
            );
        }

        throw new \Magento\Framework\Exception\NotFoundException(
            __(
                'Could not find old option label for value %1 when duplicating product %2; skipping.',
                $oldInterval->getValueId(),
                $oldProductId
            )
        );
    }
}
