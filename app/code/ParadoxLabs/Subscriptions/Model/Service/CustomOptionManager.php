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

/**
 * CustomOptionManager Class
 */
class CustomOptionManager implements \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Api\ProductCustomOptionRepositoryInterface
     */
    protected $customOptionRepository;

    /**
     * @var \Magento\Catalog\Api\Data\ProductCustomOptionInterfaceFactory
     */
    protected $customOptionFactory;

    /**
     * @var \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterfaceFactory
     */
    protected $customOptionValueFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface
     */
    protected $intervalManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * GenerateSubscriptionsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Catalog\Api\ProductCustomOptionRepositoryInterface $customOptionRepository
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterfaceFactory $customOptionFactory
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterfaceFactory $customOptionValueFactory
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource
     * @param \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Catalog\Api\ProductCustomOptionRepositoryInterface $customOptionRepository,
        \Magento\Catalog\Api\Data\ProductCustomOptionInterfaceFactory $customOptionFactory,
        \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterfaceFactory $customOptionValueFactory,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource,
        \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->helper = $helper;
        $this->customOptionRepository = $customOptionRepository;
        $this->customOptionFactory = $customOptionFactory;
        $this->customOptionValueFactory = $customOptionValueFactory;
        $this->periodSource = $periodSource;
        $this->intervalManager = $intervalManager;
        $this->config = $config;
    }

    /**
     * Create, update, or remove (as appropriate) the subscription custom option for the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    public function updateProductCustomOption(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */

        /** @var \Magento\Catalog\Model\Product\Option $existingOption */
        $existingOption = $this->getSubscriptionOption($product);

        /**
         * If subscription is not active or has only one option, remove any existing option and stop.
         */
        if ($this->config->moduleIsActive() !== true
            || (int)$product->getData('subscription_active') === 0
            || (empty($product->getData('subscription_intervals'))
                && empty($product->getData('subscription_intervals_grid')))
            || $this->skipSingleOption($product) === true) {
            $intervalsData = $this->processIntervalsAttributeValue($product);
            // Handle single-no-option-subscription case: Set interval data on the product.
            if (count($intervalsData) === 1) {
                $interval = $this->intervalManager->createIntervalModel(reset($intervalsData));
                $product = $this->intervalManager->hydrateProduct($product, $interval);
            }

            if ($existingOption instanceof \Magento\Catalog\Api\Data\ProductCustomOptionInterface) {
                $this->removeCustomOption($product, $existingOption);
            }

            return $product;
        }

        /**
         * Otherwise, add or update subscription custom option and values.
         */
        $product->setData('can_save_custom_options', true);

        $options = $product->getOptions();
        if (!is_array($options)) {
            $options = [];
        }

        if ($existingOption instanceof \Magento\Catalog\Api\Data\ProductCustomOptionInterface) {
            // Update existing option in-place.
            $this->generateSubscriptionOptionValues($product, $existingOption);

            $existingOption->setIsRequire((int)$this->isOptionRequired($product));
        } else {
            // Unshift our new opt to ensure the first option is not deleted. Otherwise we hit a logic problem in
            // product::beforeSave(), and product.has_options will always be false (no options save).
            array_unshift(
                $options,
                $this->generateSubscriptionOption($product)
            );
        }

        $options = $this->pruneEmptyOptions($options);

        $product->setOptions($options);

        return $product;
    }

    /**
     * Check if we are allowed to skip a single option, and if so, if that's all the current product has.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function skipSingleOption(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */

        if ($this->config->skipSingleOption() === true) {
            $allowOnetime = (int)$product->getData('subscription_allow_onetime');
            $intervalsCount = 0;

            $intervals = $product->getData('subscription_intervals_grid');

            if (empty($intervals)) {
                $intervals = $product->getData('subscription_intervals');
            }

            if (is_string($intervals) && $jsonDecode = json_decode($intervals, true) !== null) {
                $intervals = $jsonDecode;
            }

            if (is_array($intervals)) {
                foreach ($intervals as $interval) {
                    if (isset($interval['frequency_count']) && (int)$interval['frequency_count'] > 0) {
                        $intervalsCount++;
                    }
                }
            } elseif (is_string($intervals)) {
                $intervalsCount = count(array_filter(explode(',', $intervals)));
            }

            if (($allowOnetime === 0 && $intervalsCount === 1)
                || ($allowOnetime === 1 && $intervalsCount === 0)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find existing subscription custom option, if any.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface|null
     */
    public function getSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        return $this->intervalManager->getSubscriptionOption($product);
    }

    /**
     * Generate a fresh custom option for the current subscription settings.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface
     */
    public function generateSubscriptionOption(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product\Option $option */
        $option = $this->customOptionFactory->create();
        $option->setTitle($this->config->getSubscriptionLabel());
        $option->setType($this->config->getInputType());
        $option->setIsRequire((int)$this->isOptionRequired($product));
        $option->setSortOrder(1000);
        $option->setPrice(0);
        $option->setPriceType('fixed');
        $option->setProduct($product);
        $option->setProductSku($product->getSku());

        // Add values.
        $this->generateSubscriptionOptionValues(
            $product,
            $option
        );

        return $option;
    }

    /**
     * Create/update values on the given existing subscription custom option.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface
     */
    public function generateSubscriptionOptionValues(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
    ) {
        /** @var \Magento\Catalog\Model\Product $product */
        /** @var \Magento\Catalog\Model\Product\Option $option */

        /**
         * Get required subscription intervals.
         */
        $neededIntervalsByKey = $this->getSubscriptionIntervals($product);
        $newValues = [];

        /**
         * Get current subscription intervals for comparison.
         */
        /** @var \ParadoxLabs\Subscriptions\Model\Interval[] $currentIntervalsById */
        $currentIntervalsById = $this->intervalManager->getProductIntervals($product);

        /**
         * Compare needed and actual intervals.
         *
         * For each existing option value:
         * - If on list, remove from list
         * - If not on list, mark for deletion
         */
        $values = $option->getValues() ?: $option->getData('values');
        if (is_array($values)) {
            /** @var \Magento\Catalog\Model\Product\Option\Value $value */
            foreach ($values as $value) {
                $valueId = $this->helper->getDataValue($value, 'option_type_id');

                if (!isset($currentIntervalsById[ $valueId ])) {
                    $value = $this->deleteOptionValue($value);

                    // 'Deleted' values must be included in the option to actually persist the deletion.
                    $newValues[] = $value;
                } else {
                    $interval    = $currentIntervalsById[ $valueId ];
                    $intervalKey = $interval->getKey();

                    if (isset($neededIntervalsByKey[ $intervalKey ])) {
                        // For each found, remove it from the 'needed' array. Leaves us with only unknowns when done.
                        unset($neededIntervalsByKey[ $intervalKey ]);

                        // Note: We could set sort_order to interval here to force interval sorting. Choosing not to.
                    } else {
                        $value = $this->deleteOptionValue($value);
                    }

                    $newValues[ $intervalKey ] = $value;
                }
            }
        }

        /**
         * For any intervals remaining on the list, add corresponding values.
         */
        if (!empty($neededIntervalsByKey)) {
            foreach ($neededIntervalsByKey as $intervalKey => $interval) {
                $newValues[ $intervalKey ] = $this->generateOptionValueForInterval(
                    $product,
                    $interval
                );
            }
        }

        /**
         * Sort all the remaining values by is_delete, sort_order ASC.
         * Any deleted values must be at the end to prevent 2.1 logic deleting *all of them*.
         */
        uasort($newValues, [$this, 'sortOptionValues']);

        // Mark as subscription option for interval handler. Flag will not be persistent.
        $option->setData('subscription', true);

        // Note: Setting data.values rather than setValues() because the latter breaks manual option deletion.
        $option->setData(
            'values',
            array_values($newValues)
        );

        return $option;
    }

    /**
     * Get possible intervals for the given subscription.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface[]
     */
    public function getSubscriptionIntervals(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */

        /**
         * Get input data from the product.
         * $intervalsData should be arrays of ProductIntervalInterface-like values.
         */
        $intervalsData = $this->processIntervalsAttributeValue($product);

        /**
         * Handle one-time option special case.
         */
        $intervalsData = $this->processOneTimeAttributeValue($product, $intervalsData);

        /**
         * Build keyed map for response (easy comparisons).
         */
        $intervals = [];
        foreach ($intervalsData as $interval) {
            if (isset($interval['is_delete']) && (int)$interval['is_delete'] === 1) {
                continue;
            }

            $interval['product_id'] = $product->getData('row_id') ?: $product->getId();

            /** @var \ParadoxLabs\Subscriptions\Model\Interval $intervalModel */
            $intervalModel = $this->intervalManager->createIntervalModel(
                $interval
            );

            $intervals[$intervalModel->getKey()] = $intervalModel;
        }

        return $intervals;
    }

    /**
     * Generate custom option value for the given product and interval.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionValuesInterface
     */
    public function generateOptionValueForInterval(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterface $interval
    ) {
        /** @var \Magento\Catalog\Model\Product $product */

        /**
         * Compile value label
         */
        $count  = $interval->getFrequencyCount();
        $length = $interval->getLength() ?: (int)$product->getData('subscription_length');
        $unit   = $interval->getFrequencyUnit() ?: $product->getData('subscription_unit');

        $unitLabel  = strtolower($this->periodSource->getOptionText($unit));
        $unitPlural = strtolower($this->periodSource->getOptionTextPlural($unit));

        if ($count === 0) {
            $title = __('One Time');
        } elseif ($length > 0) {
            if ($count === 1) {
                $title = __(
                    'Every %1 for %2 %3',
                    $unitLabel,
                    $length,
                    $this->config->getInstallmentLabel()
                );
            } else {
                $title = __(
                    'Every %1 %2 for %3 %4',
                    $count,
                    $unitPlural,
                    $length,
                    $this->config->getInstallmentLabel()
                );
            }
        } else {
            if ($count === 1) {
                $title = __('Every %1', $unitLabel);
            } else {
                $title = __('Every %1 %2', $count, $unitPlural);
            }
        }

        /**
         * Create value, and assign the interval to it for later saving.
         * @see \ParadoxLabs\Subscriptions\Observer\GenerateIntervalsObserver::execute()
         */

        // Set relative sort order. Multiplier scales day/wk/mo/yr, increment avoids overlap with 'one time' (1).
        $intervalDaySort = ($count * $this->periodSource->getMultiplier($unit)) + 1;

        /** @var \Magento\Catalog\Model\Product\Option\Value $optionValue */
        $optionValue = $this->customOptionValueFactory->create();
        $optionValue->setTitle($title)
                    ->setSortOrder($intervalDaySort)
                    ->setPrice(0)
                    ->setPriceType('fixed')
                    ->setData(
                        'subscription_interval',
                        $interval
                    );

        return $optionValue;
    }

    /**
     * Sort two custom option values. Accepts array or data object.
     *
     * @param array|\Magento\Framework\DataObject $a
     * @param array|\Magento\Framework\DataObject $b
     * @return int
     */
    protected function sortOptionValues($a, $b)
    {
        /**
         * Sort by is_delete ASC, sort_order ASC
         */

        // If option is deleted, always push it toward the end.
        $aDeleted = $this->helper->getDataValue($a, 'is_delete');
        $bDeleted = $this->helper->getDataValue($b, 'is_delete');

        if ($aDeleted xor $bDeleted) {
            return $aDeleted ? 1 : -1;
        }

        // Otherwise, sort by order.
        $aSort = $this->helper->getDataValue($a, 'sort_order');
        $bSort = $this->helper->getDataValue($b, 'sort_order');

        if ($aSort === $bSort) {
            return 0;
        }

        return $aSort > $bSort ? 1 : -1;
    }

    /**
     * Delete/mark the given value for deletion.
     *
     * @param array|\Magento\Framework\DataObject $value
     * @return array|\Magento\Framework\DataObject
     */
    protected function deleteOptionValue($value)
    {
        if ($value instanceof \Magento\Framework\DataObject) {
            $value->setData('is_delete', 1);
        } elseif (is_array($value)) {
            $value['is_delete'] = 1;
        }

        return $value;
    }

    /**
     * Get 'subscription_intervals' attribute value; make consistent for different possible cases.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     */
    protected function processIntervalsAttributeValue(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */

        /**
         * Get intervals input (when possible)
         */
        if ($product->hasData('subscription_intervals_grid')) {
            // If we get grid values, use them directly.
            $intervals = $product->getData('subscription_intervals_grid');
        } elseif ($this->intervalManager->isProductIntervalGridEligible($product)) {
            // If we get no grid values, but should have, load any existing intervals so they aren't lost.
            $intervals = [];

            /** @var \ParadoxLabs\Subscriptions\Model\Subscription[] $existingIntervalModels */
            $existingIntervalModels = $this->intervalManager->getProductIntervals($product);
            foreach ($existingIntervalModels as $intervalModel) {
                $intervals[] = $intervalModel->getData();
            }
        }

        if (!isset($intervals) || empty($intervals)) {
            // Otherwise, fall back to legacy handling.
            $intervals = $product->getData('subscription_intervals');
        }

        /**
         * Handle legacy and non-grid input
         */
        if (is_string($intervals)) {
            /**
             * Allow JSON input ... not used by the extension, but maybe useful for API product creation.
             * Pass interval data arrays into subscription_intervals, generation happens from there. Note you will not
             * get the JSON back out. Format:
             *
             * [
             *     {
             *         "frequency_count": 1,
             *         "frequency_unit": "day",
             *         "length": null,
             *         "installment_price": null,
             *         "adjustment_price": null
             *     }
             * ]
             */
            $jsonDecode = json_decode($intervals, true);
            if ($jsonDecode !== null && $intervals[0] === '[') {
                $intervals = $jsonDecode;
            } elseif (!empty($product->getData('subscription_unit'))) {
                /**
                 * It's not JSON? Process it as comma-separated frequency_count values.
                 */
                $intervalCounts = explode(',', $intervals);
                $intervals      = [];
                foreach ($intervalCounts as $count) {
                    $intervals[] = [
                        'frequency_count' => $count,
                    ];
                }
            }
        }

        /**
         * Sanity check.
         */
        if (!is_array($intervals)) {
            $intervals = [];
        }

        /**
         * Set attribute value to comma-separated intervals for legacy storage
         * AND Dispell empty strings ("" becomes 0 -> bad)
         */
        $intervalCounts = [];
        foreach ($intervals as $i => $intervalData) {
            if (isset($intervalData['frequency_count'])) {
                $intervalCounts[] = $intervalData['frequency_count'];
            }

            foreach ($intervalData as $key => $value) {
                if ($value === '') {
                    $intervals[$i][$key] = null;
                }
            }
        }
        $product->setData('subscription_intervals', implode(',', $intervalCounts));

        return $intervals;
    }

    /**
     * Handle one-time option special case (when applicable).
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param array $intervalsData
     * @return array
     */
    protected function processOneTimeAttributeValue(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        array $intervalsData
    ) {
        /** @var \Magento\Catalog\Model\Product $product */

        if ((int)$product->getData('subscription_allow_onetime') === 1) {
            if ($this->config->defaultToOneTime() === false) {
                array_unshift(
                    $intervalsData,
                    [
                        'frequency_count' => 0,
                    ]
                );

                $haveZero = false;
            } else {
                // If we're defaulting to one-time, we don't want any option for it.
                $haveZero = true;
            }

            // Make sure we don't have multiple zero options.
            foreach ($intervalsData as $k => $interval) {
                if (isset($interval['frequency_count']) && (int)$interval['frequency_count'] === 0) {
                    if ($haveZero === true) {
                        unset($intervalsData[ $k ]);
                    } else {
                        $haveZero = true;
                    }
                }
            }
        }

        return $intervalsData;
    }

    /**
     * Remove the given custom option from the given product.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $existingOption
     * @return \Magento\Catalog\Api\Data\ProductInterface
     */
    protected function removeCustomOption(
        \Magento\Catalog\Api\Data\ProductInterface $product,
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $existingOption
    ) {
        /** @var \Magento\Catalog\Model\Product\Option $existingOption */

        /**
         * Delete and mark deleted
         */
        $existingOption->setData('is_delete', 1);
        $this->customOptionRepository->delete($existingOption);

        /**
         * Remove the option from the product's options array. This prevents "No such entity." on 2.1.10+.
         */
        $options = $product->getOptions();
        foreach ($options as $k => $option) {
            if ($option->getOptionId() === $existingOption->getOptionId()) {
                unset($options[ $k ]);
            }
        }
        $product->setOptions($options);

        return $product;
    }

    /**
     * Prune any empty custom options out of the options array.
     *
     * Could be handled more gracefully, but this covers case where all subscription options were removed but the
     * product itself is still subscription-enabled. Left with a custom option with all values deleted.
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface[] $options
     * @return \Magento\Catalog\Api\Data\ProductCustomOptionInterface[]
     */
    protected function pruneEmptyOptions($options)
    {
        /**
         * Sanity check: If all values are deleted, remove the option entirely.
         */

        /** @var \Magento\Catalog\Model\Product\Option $option */
        foreach ($options as $key => $option) {
            if ($option->hasData('values') === false) {
                continue;
            }

            $allDeleted = true;
            foreach ($option->getData('values') as $value) {
                if ($this->helper->getDataValue($value, 'is_delete') != 1) {
                    $allDeleted = false;
                    break;
                }
            }

            if ($allDeleted === true) {
                $option->setData('is_delete', 1);
                unset($options[$key]);
            }
        }

        return $options;
    }

    /**
     * Check whether the subscription custom option should be required for the given product and current config.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    protected function isOptionRequired(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        /** @var \Magento\Catalog\Model\Product $product */

        // If we're not adding a one-time option and it is allowed, the dropdown should be optional.
        if ($this->config->defaultToOneTime() && $product->getData('subscription_allow_onetime')) {
            return false;
        }

        return true;
    }
}
