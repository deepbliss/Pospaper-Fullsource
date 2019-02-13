<?php

namespace Pos\Subscriptions\Model\Service;

class CustomOptionManager extends \ParadoxLabs\Subscriptions\Model\Service\CustomOptionManager
{
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
                $title = __('%1 %2', $count, $unitLabel.' (Most common)');
            } else {
                $title = __('%1 %2', $count, $unitPlural);
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
        $option->setIsRequire(0);
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
         * Handle one-time special case: Interval with frequency === 0 makes 'One time' option
         */
        /*
        if ((int)$product->getData('subscription_allow_onetime') === 1) {
            array_unshift(
                $intervalsData,
                [
                    'frequency_count' => 0,
                ]
            );

            // Make sure we ONLY have one zero.
            $haveZero = false;
            foreach ($intervalsData as $k => $interval) {
                if (isset($interval['frequency_count']) && (int)$interval['frequency_count'] === 0) {
                    if ($haveZero === true) {
                        unset($intervalsData[$k]);
                    } else {
                        $haveZero = true;
                    }
                }
            }
        }
        */

        // Make sure we don't have any zeros.
        foreach ($intervalsData as $k => $interval) {
            if (isset($interval['frequency_count']) && (int)$interval['frequency_count'] === 0) {
                unset($intervalsData[$k]);
            }
        }

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
}
