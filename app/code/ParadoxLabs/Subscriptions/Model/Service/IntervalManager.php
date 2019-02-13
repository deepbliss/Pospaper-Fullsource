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

use ParadoxLabs\Subscriptions\Api\IntervalManagerInterface;

/**
 * IntervalManager Class
 */
class IntervalManager implements IntervalManagerInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface
     */
    protected $intervalRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory
     */
    protected $intervalFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * IntervalManager constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->intervalRepository = $intervalRepository;
        $this->intervalFactory = $intervalFactory;
        $this->helper = $helper;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->config = $config;
    }

    /**
     * Add/remove intervals to match subscription option values for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return IntervalManagerInterface
     */
    public function updateProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $intervalsByValueId = $this->getProductIntervals($product);

        /**
         * Find existing option and values
         */

        /** @var \Magento\Catalog\Model\Product\Option $existingOption */
        $existingOption = $this->getSubscriptionOption($product, $intervalsByValueId);

        // If we still have no option, abort, remove anything existing.
        if ($existingOption === null) {
            return $this->removeProductIntervals($product);
        }

        $valuesById = $this->getCustomOptionValues($existingOption);

        /**
         * Identify intervals that are not in option.values, by ID (must be removed) -- remove them.
         */
        $removedIntervals = array_diff_key(
            $intervalsByValueId,
            $valuesById
        );

        foreach ($removedIntervals as $interval) {
            $this->intervalRepository->delete($interval);
        }

        /**
         * Identify option.values that are not in intervals (must be added) -- add them.
         */
        $addedValues = array_diff_key(
            $valuesById,
            $intervalsByValueId
        );

        foreach ($addedValues as $value) {
            try {
                $this->saveIntervalForOptionValue($existingOption, $value);
            } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
                $this->helper->log('subscriptions', $e->getMessage());
            }
        }

        /**
         * Any options+intervals that already exist are left as-is. Their info won't change under normal circumstances.
         */

        return $this;
    }

    /**
     * Remove all stored intervals for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return IntervalManagerInterface
     */
    public function removeProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $productId = $product->getData('row_id') ?: $product->getId();

        if ((int)$productId > 0) {
            $intervals = $this->intervalRepository->getIntervalsByProductId($productId);

            if ($intervals->getTotalCount() > 0) {
                /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval */
                foreach ($intervals->getItems() as $interval) {
                    $this->intervalRepository->delete($interval);
                }
            }
        }

        return $this;
    }

    /**
     * Find the given product's subscription custom option, if any.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervals
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null
     */
    public function getSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product, $intervals = null)
    {
        if ($intervals === null) {
            $searchResults = $this->intervalRepository->getIntervalsByProductId(
                $product->getData('row_id') ?: $product->getId()
            );
            $intervals = $searchResults->getItems();
        }

        /** @var \Magento\Catalog\Model\Product\Option[] $options */
        $options = $product->getOptions();

        // Try to find the option based on existing intervals.
        if (count($intervals) > 0) {
            /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $firstInterval */
            $firstInterval = current($intervals);
            $optionId = $firstInterval->getOptionId();

            foreach ($options as $option) {
                if ($option->getOptionId() === $optionId) {
                    return $option;
                }
            }
        }

        if ($options !== null) {
            // If loading option by interval didn't work, look for subscription flag.
            foreach ($options as $option) {
                if ($option->getData('subscription') === true) {
                    return $option;
                }
            }

            // If that didn't work, look for label.
            foreach ($options as $option) {
                if ($option->getTitle() === $this->config->getSubscriptionLabel()) {
                    return $option;
                }
            }
        }

        return null;
    }

    /**
     * Get intervals for the given product (if any).
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface[]
     */
    public function getProductIntervals(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $intervalsByValueId = [];

        $productId = $product->getData('row_id') ?: $product->getId();
        if ((int)$productId > 0) {
            $searchResults = $this->intervalRepository->getIntervalsByProductId($productId);

            /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval */
            foreach ($searchResults->getItems() as $interval) {
                $intervalsByValueId[$interval->getValueId()] = $interval;
            }
        }

        return $intervalsByValueId;
    }

    /**
     * Get non-deleted values from the given option, keyed by value ID.
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface[]
     */
    protected function getCustomOptionValues(\Magento\Catalog\Api\Data\ProductCustomOptionInterface $option)
    {
        /** @var \Magento\Catalog\Model\Product\Option $option */

        $valuesById = [];
        foreach ($option->getData('values') as $value) {
            $deleted = (int)$this->helper->getDataValue($value, 'is_delete');

            if ($deleted !== 1) {
                $valueId                = $this->helper->getDataValue($value, 'option_type_id');
                $valuesById[ $valueId ] = $value;
            }
        }

        return $valuesById;
    }

    /**
     * Create an Interval model from the given data.
     *
     * @param array $data
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     */
    public function createIntervalModel(array $data)
    {
        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval */
        $interval = $this->intervalFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $interval,
            $data,
            \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface::class
        );

        return $interval;
    }

    /**
     * Save an interval record for the given option and value (if possible).
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface|array $value
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveIntervalForOptionValue(
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option,
        $value
    ) {
        /** @var \Magento\Catalog\Model\Product\Option $option */

        /** @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval */
        $interval = $this->helper->getDataValue($value, 'subscription_interval');

        if (($interval instanceof \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface) === false) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Did not find expected ProductIntervalInterface for product '
                    . $option->getProductId() . ' / value '
                    . $this->helper->getDataValue($value, 'option_type_id')
                )
            );
        }

        /**
         * Save interval from option value, with IDs.
         *
         * NOTE: Interval data is set during value generation in
         * \ParadoxLabs\Subscriptions\Model\Service\CustomOptionManager->generateOptionValueForInterval().
         */
        $interval->setProductId($option->getProductId());
        $interval->setOptionId($this->helper->getDataValue($value, 'option_id'));
        $interval->setValueId($this->helper->getDataValue($value, 'option_type_id'));
        $this->intervalRepository->save($interval);

        return $interval;
    }

    /**
     * Check defaults -- set values in place of interval nulls.
     *
     * Done for no good backwards-compat/consistency reasons.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervalData
     * @return array
     */
    public function hydrateIntervalData(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        array $intervalData
    ) {
        /** @var \Magento\Catalog\Model\Product $product */

        foreach ($this->getAttributeIntervalMap() as $attributeCode => $intervalField) {
            if (!isset($intervalData[ $intervalField ]) && $product->getData($attributeCode) != 0) {
                $intervalData[ $intervalField ] = $product->getData($attributeCode);
            }
        }

        return $intervalData;
    }

    /**
     * Check defaults -- remove matches from interval values.
     *
     * Done for no good backwards-compat/consistency reasons.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervalData
     * @return array
     */
    public function dehydrateIntervalData(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        array $intervalData
    ) {
        /** @var \Magento\Catalog\Model\Product $product */

        foreach ($this->getAttributeIntervalMap() as $attributeCode => $intervalField) {
            if (isset($intervalData[ $intervalField ])
                && $intervalData[ $intervalField ] == $product->getData($attributeCode)) {
                $intervalData[ $intervalField ] = null;
            }
        }

        return $intervalData;
    }

    /**
     * Set interval values on product attributes. Used for single no-option subscription saving.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function hydrateProduct(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
    ) {
        /** @var \Magento\Catalog\Model\Product $product */
        /** @var \ParadoxLabs\Subscriptions\Model\Interval $interval */

        foreach ($this->getAttributeIntervalMap() as $attributeCode => $intervalField) {
            $product->setData($attributeCode, $interval->getData($intervalField));
        }

        return $product;
    }

    /**
     * Get relations between product attributes and interval columns.
     *
     * @return array
     */
    public function getAttributeIntervalMap()
    {
        return static::ATTRIBUTE_INTERVAL_MAP;
    }

    /**
     * Determine whether the given product is eligible for a subscription intervals grid.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function isProductIntervalGridEligible(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        // Bundle products do not support custom options, so cannot have multiple intervals. No grid for them.
        if ($product->getTypeId() === \Magento\Bundle\Model\Product\Type::TYPE_CODE) {
            return false;
        }

        return true;
    }
}
