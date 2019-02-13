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

namespace ParadoxLabs\Subscriptions\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Price;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;

/**
 * SubscriptionIntervals Class
 */
class SubscriptionIntervals extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $locator;

    /**
     * @var \Magento\Framework\Stdlib\ArrayManager
     */
    protected $arrayManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface
     */
    protected $intervalRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface
     */
    protected $customOptionManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface
     */
    protected $intervalManager;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * SubscriptionIntervals constructor.
     *
     * @param \Magento\Catalog\Model\Locator\LocatorInterface $locator
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource
     * @param \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository
     * @param \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface $customOptionManager
     * @param \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \Magento\Catalog\Model\Locator\LocatorInterface $locator,
        \Magento\Framework\Stdlib\ArrayManager $arrayManager,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource,
        \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository,
        \ParadoxLabs\Subscriptions\Api\CustomOptionManagerInterface $customOptionManager,
        \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->locator      = $locator;
        $this->arrayManager = $arrayManager;
        $this->periodSource = $periodSource;
        $this->intervalRepository = $intervalRepository;
        $this->customOptionManager = $customOptionManager;
        $this->intervalManager = $intervalManager;
        $this->config = $config;
    }

    /**
     * Modify produt data for form.
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        if ($this->isProductIntervalGridEligible() === false || $this->locator->getProduct()->getId() < 1) {
            return $data;
        }

        $intervalsData = $this->getIntervalsData();

        return array_replace_recursive(
            $data,
            [
                $this->locator->getProduct()->getId() => [
                    static::DATA_SOURCE_DEFAULT => [
                        'subscription_intervals_grid' => $intervalsData,
                    ],
                ],
            ]
        );
    }

    /**
     * Get intervals data for grid.
     *
     * @return array
     */
    public function getIntervalsData()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->locator->getProduct();

        /**
         * Load intervals data
         */
        $intervals = $this->intervalRepository->getIntervalsByProductId(
            $product->getData('row_id') ?: $product->getId()
        );

        $intervalsData = [];
        /** @var \ParadoxLabs\Subscriptions\Model\Interval $interval */
        foreach ($intervals->getItems() as $interval) {
            if ($interval->getFrequencyCount() > 0) {
                $intervalsData[] = $interval->getData();
            }
        }

        /**
         * Check single-option case
         */
        if (empty($intervalsData)
            && !empty($product->getData('subscription_intervals'))
            && strpos($product->getData('subscription_intervals'), ',') === false) {
            $intervalsData[] = [
                'frequency_count' => $product->getData('subscription_intervals'),
                'frequency_unit' => $product->getData('subscription_unit'),
            ];
        }

        /**
         * Check defaults -- distill values among interval nulls.
         */
        foreach ($intervalsData as $k => $intervalData) {
            $intervalsData[ $k ] = $this->intervalManager->hydrateIntervalData($product, $intervalData);

            if (isset($intervalsData[ $k ]['installment_price'])) {
                $intervalsData[ $k ]['installment_price'] = $this->coercePrecision(
                    $intervalsData[ $k ]['installment_price']
                );
            }

            if (isset($intervalsData[ $k ]['adjustment_price'])) {
                $intervalsData[ $k ]['adjustment_price'] = $this->coercePrecision(
                    $intervalsData[ $k ]['adjustment_price']
                );
            }
        }

        return $intervalsData;
    }

    /**
     * Modify product form for subscription management. Assumes subscription attributes are assigned to attr set.
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $attributePath = $this->arrayManager->findPath(
            'container_subscription_intervals',
            $meta,
            null,
            'children'
        );

        // Skip grid if we don't have subscription options, or bundle product.
        if ($attributePath === null || $this->isProductIntervalGridEligible() === false) {
            return $meta;
        }

        $containerPath = substr(
            $attributePath,
            0,
            strrpos($attributePath, ArrayManager::DEFAULT_PATH_DELIMITER)
        );

        $container = $this->arrayManager->get($containerPath, $meta);

        $customField = [
            'subscription_intervals_info' => $this->getIntervalsGridInfo(),
            'subscription_intervals_grid' => $this->getIntervalsGrid(),
        ];

        /**
         * Inject grid
         */
        $container += $customField;

        $meta = $this->arrayManager->replace(
            $containerPath,
            $meta,
            $container
        );

        /**
         * Remove other attributes to avoid confusion
         */
        $meta = $this->removeGridAttributes($meta);

        return $meta;
    }

    /**
     * Get the intervals input grid definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGrid($sortOrder = 1001)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Subscription Option'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => 'is_delete',
                        'deleteValue' => true,
                        'defaultRecord' => false,
                        'sortOrder' => $sortOrder,
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => $this->getIntervalsGridColumns(),
                ],
            ],
        ];
    }

    /**
     * Get currency symbol for price fields. We assume it'll always be before the price, same as core.
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->locator->getStore();

        return $store->getBaseCurrency()
                     ->getCurrencySymbol();
    }

    /**
     * Get intervals grid documentation blurb (explain fields/usage).
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridInfo($sortOrder = 1000)
    {
        $content = [];

        if ($this->customOptionManager->skipSingleOption($this->locator->getProduct())) {
            $content[] = __('If there is only one option, the product will always be a subscription (no dropdown).');
        }

        $content[] = __('Leave <b>length</b> empty to run until canceled.');
        $content[] = __('Leave <b>prices</b> empty to use normal product pricing.');
        $content[] = __('<b>Adjustment Price</b> can be used to raise or lower the initial purchase price.');

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                    ],
                ],
            ],
            'children' => [
                'subscription_intervals_info' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Subscription Options'),
                                'formElement' => Container::NAME,
                                'componentType' => Container::NAME,
                                'template' => 'ui/form/components/complex',
                                'content' => implode('<br />', $content),
                                'sortOrder' => $sortOrder,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the intervals grid columns.
     *
     * @return array
     */
    public function getIntervalsGridColumns()
    {
        return [
            'frequency_count' => $this->getIntervalsGridColFrequencyCount(),
            'frequency_unit' => $this->getIntervalsGridColFrequencyUnit(),
            'length' => $this->getIntervalsGridColLength(),
            'installment_price' => $this->getIntervalsGridColInstallmentPrice(),
            'adjustment_price' => $this->getIntervalsGridColAdjustmentPrice(),
            'actionDelete' => $this->getIntervalsGridColDelete(),
        ];
    }

    /**
     * Get the 'frequency count' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColFrequencyCount($sortOrder = 10)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Frequency'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => 'frequency_count',
                        'dataType' => Number::NAME,
                        'addbefore' => __('Every'),
                        'placeholder' => '',
                        'validation' => [
                            'validate-greater-than-zero' => true,
                        ],
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the 'frequency unit' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColFrequencyUnit($sortOrder = 20)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => ' ',
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'dataType' => Text::NAME,
                        'dataScope' => 'frequency_unit',
                        'options' => $this->periodSource->toOptionArrayPlural(),
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the 'length' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColLength($sortOrder = 30)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Length'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => 'length',
                        'dataType' => Number::NAME,
                        'addbefore' => __('for'),
                        'placeholder' => '∞',
                        'addafter' => __(
                            $this->config->getInstallmentLabel()
                        ),
                        'validation' => [
                            'validate-greater-than-zero' => true,
                        ],
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the 'installment price' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColInstallmentPrice($sortOrder = 40)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataType' => Price::NAME,
                        'label' => __('Installment Price'),
                        'enableLabel' => true,
                        'dataScope' => 'installment_price',
                        'addbefore' => __(
                            'of %1',
                            $this->getCurrencySymbol()
                        ),
                        'placeholder' => '',
                        'validation' => [
                            'validate-number' => true,
                        ],
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the 'adjustment price' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColAdjustmentPrice($sortOrder = 50)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataType' => Price::NAME,
                        'label' => __('Adjustment Price'),
                        'enableLabel' => true,
                        'dataScope' => 'adjustment_price',
                        'addbefore' => __(
                            'plus %1',
                            $this->getCurrencySymbol()
                        ),
                        'placeholder' => '',
                        'addafter' => __('upfront'),
                        'validation' => [
                            'validate-number' => true,
                        ],
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the 'delete' column definition.
     *
     * @param int $sortOrder
     * @return array
     */
    public function getIntervalsGridColDelete($sortOrder = 60)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'actionDelete',
                        'dataType' => Text::NAME,
                        'label' => ' ',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * When we're rendering the grid, remove the normal attributes to avoid confusion.
     *
     * Data will be manipulated on the backend to keep everything in the right spots.
     *
     * @param array $meta
     * @return array
     */
    public function removeGridAttributes(array $meta)
    {
        foreach (array_keys($this->intervalManager->getAttributeIntervalMap()) as $attr) {
            $meta = $this->arrayManager->remove(
                $this->arrayManager->findPath(
                    'container_' . $attr,
                    $meta,
                    null,
                    'children'
                ),
                $meta
            );
        }

        return $meta;
    }

    /**
     * Should the current product show the subscription intervals grid?
     *
     * @return bool
     */
    public function isProductIntervalGridEligible()
    {
        return $this->intervalManager->isProductIntervalGridEligible($this->locator->getProduct());
    }

    /**
     * Coerce prices to 2 or 4 decimals depending on the precision actually needed.
     *
     * This is to avoid prices always showing as 0.0000 when there's no need.
     *
     * @param int|float $value
     * @return string
     */
    protected function coercePrecision($value)
    {
        $decimal = (float)(abs($value) - floor(abs($value)));

        if (strlen($decimal) > 4) {
            return sprintf('%0.4f', $value);
        }

        return sprintf('%0.2f', $value);
    }
}
