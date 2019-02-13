<?php

namespace Pos\Subscriptions\Model\Service;

class ItemManager extends \ParadoxLabs\Subscriptions\Model\Service\ItemManager
{
    protected $pos_helper;

    public function __construct(
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Catalog\Helper\Product\Configuration $productConfig,
        \ParadoxLabs\Subscriptions\Model\Service\CurrencyManager $currencyManager,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\Subscriptions\Api\ProductIntervalRepositoryInterface $intervalRepository,
        \Magento\Framework\DataObject\Factory $dataObjectFactory,
        \Pos\Subscriptions\Helper\Data $posHelper
    ) {
        parent::__construct($helper, $productConfig, $currencyManager, $config, $intervalRepository, $dataObjectFactory);
        $this->pos_helper = $posHelper;
    }

    /**
     * Calculate price for a subscription item.
     *
     * @param \Magento\Framework\Model\AbstractExtensibleModel $item
     * @param int $installment
     * @param string $baseCurrency Website base currency code (convert from)
     * @param string $quoteCurrency Cart currency code (convert to)
     * @return float
     */
    public function calculatePrice(
        \Magento\Framework\Model\AbstractExtensibleModel $item,
        $installment,
        $baseCurrency = null,
        $quoteCurrency = null
    ) {
        /** @var \Magento\Sales\Model\Order\Item|\Magento\Quote\Model\Quote\Item $item */

        $product = $item->getProduct();
        $price   = $product->getFinalPrice();
        $basePrice = $product->getFinalPrice();
        $qty = $item->getQty();
        $tierPrice = $product->getTierPrice($qty);
        $finalPrice = $price;
        if (is_numeric($tierPrice)) {
            $finalPrice = min($price, $tierPrice);
        }

        $subscriptionDiscount = $this->pos_helper->getSubscriptionDiscount();
        $price = round(($finalPrice - ($finalPrice * $subscriptionDiscount)/100),2);
        $basePrice = $price;

        // Take subscription price to start (if any); otherwise, use normal product price.
        $installmentPrice = $this->getInstallmentPrice($item);
        if ($installmentPrice !== null) {
            $basePrice = $installmentPrice;
            $price = $this->currencyManager->convertPriceCurrency(
                $installmentPrice,
                $baseCurrency,
                $quoteCurrency
            );
        }

        // If this is the first billing, add the initial adjustment fee (if any).
        $adjustmentPrice = $this->getAdjustmentPrice($item);
        if ($installment === 1 && $adjustmentPrice !== null) {
            $basePrice += $adjustmentPrice;
            $price += $this->currencyManager->convertPriceCurrency(
                $adjustmentPrice,
                $baseCurrency,
                $quoteCurrency
            );
        }

        // Account for custom option pricing
        $configuredPrice = $this->getItemOptionsValue($basePrice, $item);
        if ($configuredPrice > 0) {
            $price += $this->currencyManager->convertPriceCurrency(
                $configuredPrice,
                $baseCurrency,
                $quoteCurrency
            );
        }

        // Return calculated value. Final price must not be negative.
        return max($price, 0.0);
    }
}