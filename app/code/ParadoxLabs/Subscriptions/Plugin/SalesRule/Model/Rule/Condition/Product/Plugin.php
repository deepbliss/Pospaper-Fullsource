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

namespace ParadoxLabs\Subscriptions\Plugin\SalesRule\Model\Rule\Condition\Product;

use ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface;

/**
 * Plugin Class
 */
class Plugin
{
    const COND_SUBSCRIPTION_INTERVAL = 'quote_item_subscription_interval';
    const COND_SUBSCRIPTION_RUN_COUNT = 'quote_item_subscription_run_count';

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * Plugin constructor.
     *
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
    ) {
        $this->productRepository = $productRepository;
        $this->itemManager = $itemManager;
    }

    /**
     * Add subscription interval to Sales Rule conditions list
     *
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterLoadAttributeOptions(
        \Magento\SalesRule\Model\Rule\Condition\Product $subject,
        $result
    ) {
        $attributes = $result->getAttributeOption();
        $attributes[static::COND_SUBSCRIPTION_INTERVAL] = __('Subscription Interval');
        $attributes[static::COND_SUBSCRIPTION_RUN_COUNT] = __('Subscription Installment Number');

        asort($attributes);

        $result->setAttributeOption($attributes);

        return $result;
    }

    /**
     * Add subscription interval and run count to product data upon Sales Rule validation.
     *
     * @param \Magento\SalesRule\Model\Rule\Condition\Product $subject
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return array
     */
    public function beforeValidate(
        \Magento\SalesRule\Model\Rule\Condition\Product $subject,
        \Magento\Framework\Model\AbstractModel $model
    ) {
        /** @var \Magento\Quote\Model\Quote\Item $model */
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $model->getProduct();
        if (!$product instanceof \Magento\Catalog\Model\Product) {
            $product = $this->productRepository->getById($model->getProductId());
        }

        $product->setData(
            static::COND_SUBSCRIPTION_INTERVAL,
            $this->itemManager->getFrequencyCount($model)
        );

        $product->setData(
            static::COND_SUBSCRIPTION_RUN_COUNT,
            $this->itemManager->getSubscriptionRunCount($model)
        );

        $model->setProduct($product);

        return [
            $model
        ];
    }
}
