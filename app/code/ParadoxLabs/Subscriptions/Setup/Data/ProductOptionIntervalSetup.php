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

namespace ParadoxLabs\Subscriptions\Setup\Data;

use Magento\Store\Model\Store as StoreModel;

/**
 * ProductOptionIntervalSetup Class
 */
class ProductOptionIntervalSetup
{
    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory
     */
    protected $optionCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory
     */
    protected $intervalFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface
     */
    protected $intervalRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory
     */
    protected $intervalCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $productResourceFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * ProductOptionIntervalSetup constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory
     * @param \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory
     * @param \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory $intervalCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\ProductFactory $productResourceFactory
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\Option\CollectionFactory $optionCollectionFactory,
        \ParadoxLabs\Subscriptions\Api\Data\ProductIntervalInterfaceFactory $intervalFactory,
        \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Interval\CollectionFactory $intervalCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\ProductFactory $productResourceFactory,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->helper = $helper;
        $this->intervalFactory = $intervalFactory;
        $this->intervalRepository = $intervalRepository;
        $this->intervalCollectionFactory = $intervalCollectionFactory;
        $this->productResourceFactory = $productResourceFactory;
        $this->config = $config;
    }

    /**
     * If there are no subscription product intervals stored, go through products looking for any subscription custom
     * options and create intervals for them.
     *
     * The intervals allow us to associate subscription info to custom option values, rather than going by convention.
     *
     * @return void
     */
    public function createOptionIntervals()
    {
        /**
         * Skip generation if any intervals exist. (Run upgrade process only once.)
         */
        if ($this->getIntervalCount() > 0) {
            return;
        }

        /**
         * Get subscription custom options and values.
         *
         * Note: Intentionally taking values from storeId=0 only. Extension would never produce subscription options in
         * any other scope.
         */
        $optionCollection = $this->optionCollectionFactory->create();
        $optionCollection->addTitleToResult(StoreModel::DEFAULT_STORE_ID);
        $optionCollection->addFieldToFilter('default_option_title.title', $this->config->getSubscriptionLabel());
        $optionCollection->setOrder('product_id', $optionCollection::SORT_ORDER_ASC);
        $optionCollection->addValuesToResult(StoreModel::DEFAULT_STORE_ID);

        /**
         * For each subscription custom option, generate intervals for its values.
         */

        /** @var \Magento\Catalog\Model\Product\Option $option */
        foreach ($optionCollection as $option) {
            // Loading attribute values directly because it's faster, and ProductRepository chokes during upgrade.
            $productData = $this->getProductResource()->getAttributeRawValue(
                $option->getProductId(),
                [
                    'subscription_active',
                    'subscription_unit',
                    'subscription_length',
                ],
                StoreModel::DEFAULT_STORE_ID
            );

            if (isset($productData['subscription_active']) && (int)$productData['subscription_active'] === 1) {
                $this->generateIntervals($option, $productData);
            }
        }
    }

    /**
     * Generate subscription intervals for the given product and custom option.
     *
     * @param \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option
     * @param array $productData
     * @return void
     */
    protected function generateIntervals(
        \Magento\Catalog\Api\Data\ProductCustomOptionInterface $option,
        $productData
    ) {
        /** @var \Magento\Catalog\Model\Product\Option $option */

        if (!is_array($option->getValues())) {
            return;
        }

        foreach ($option->getValues() as $value) {
            $interval = $this->intervalFactory->create();
            $interval->setProductId($option->getProductId());
            $interval->setOptionId($option->getOptionId());
            $interval->setValueId($value->getOptionTypeId());

            $interval->setFrequencyCount(
                $this->helper->getIntervalFromString(
                    $value->getTitle(),
                    (string)__('Every %1', $productData['subscription_unit'])
                )
            );

            // Note: We intentionally aren't setting other interval fields. Nulls fall back to product attributes.

            $this->intervalRepository->save($interval);
        }
    }

    /**
     * Get the number of existing intervals.
     *
     * @return int
     */
    protected function getIntervalCount()
    {
        $intervalCollection = $this->intervalCollectionFactory->create();

        return $intervalCollection->getSize();
    }

    /**
     * Get product resource model.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product
     */
    protected function getProductResource()
    {
        if ($this->productResource === null) {
            $this->productResource = $this->productResourceFactory->create();
        }

        return $this->productResource;
    }
}
