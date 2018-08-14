<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Model;

use Amasty\Checkout\Api\ItemManagementInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\ShippingMethodManagementInterface;

class ItemManagement implements ItemManagementInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;
    /**
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalRepository;
    /**
     * @var CustomerCart
     */
    protected $cart;
    /**
     * @var TotalsFactory
     */
    protected $totalsFactory;
    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;
    /**
     * @var \Amasty\Checkout\Helper\Item
     */
    protected $itemHelper;
    /**
     * @var \Magento\Quote\Api\PaymentMethodManagementInterface
     */
    protected $paymentMethodManagement;
    /**
     * @var \Magento\Quote\Api\ShipmentEstimationInterface
     */
    protected $shipmentEstimation;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalRepository,
        CustomerCart $cart,
        \Amasty\Checkout\Model\TotalsFactory $totalsFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Amasty\Checkout\Helper\Item $itemHelper,
        \Magento\Quote\Api\ShipmentEstimationInterface $shipmentEstimation,
        \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->cart = $cart;
        $this->totalsFactory = $totalsFactory;
        $this->jsonHelper = $jsonHelper;
        $this->imageHelper = $imageHelper;
        $this->itemHelper = $itemHelper;
        $this->paymentMethodManagement = $paymentMethodManagement;
        $this->shipmentEstimation = $shipmentEstimation;
    }

    public function remove($cartId, $itemId, AddressInterface $address)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        $quote = $this->cartRepository->get($cartId);
        $initialVirtualState = $quote->isVirtual();


        $item = $quote->getItemById($itemId);

        if ($item && $item->getId()) {
            $quote->deleteItem($item);
            $this->cartRepository->save($quote);
        }

        if ($quote->isVirtual() && !$initialVirtualState) {
            return false;
        }

        $shippingMethods = $this->shipmentEstimation->estimateByExtendedAddress(
            $cartId,
            $address
        );

        $totals = $this->totalsFactory->create(['data' => [
            'totals' => $this->cartTotalRepository->get($cartId),
            'shipping' => $shippingMethods,
            'payment' => $this->paymentMethodManagement->getList($cartId)
        ]]);

        return $totals;
    }

    public function update($cartId, $itemId, $formData, AddressInterface $address)
    {
        /** @var \Magento\Quote\Model\Quote $quote */

        $quote = $this->cartRepository->get($cartId);
        $initialVirtualState = $quote->isVirtual();

        $this->cart->setQuote($quote);

        parse_str($formData, $params);

        if (!isset($params['options'])) {
            $params['options'] = [];
        }

        if (isset($params['qty'])) {
            $params['qty'] = (int)$params['qty'];
        }

        $quoteItem = $this->cart->getQuote()->getItemById($itemId);
        if (!$quoteItem) {
            throw new LocalizedException(__('We can\'t find the quote item.'));
        }

        $item = $this->cart->updateItem($itemId, new DataObject($params));
        if (is_string($item)) {
            throw new LocalizedException(__($item));
        }
        if ($item->getHasError()) {
            throw new LocalizedException(__($item->getMessage()));
        }

        $this->cart->save();

        if ($quote->isVirtual() && !$initialVirtualState) {
            return false;
        }

        $shippingMethods = $this->shipmentEstimation->estimateByExtendedAddress(
            $cartId,
            $address
        );

        $items = $this->cartTotalRepository->get($cartId);

        $optionsData = [];

        foreach ($quote->getAllVisibleItems() as $item) {
            $optionsData[$item->getId()] = $this->itemHelper->getItemOptionsConfig($quote, $item);
        }

        $imageData = [];

        foreach ($quote->getAllVisibleItems() as $item) {
            $imageData[$item->getId()] = [
                'src' => $this->imageHelper->init(
                    $item->getProduct(), 'mini_cart_product_thumbnail', [
                        'type' => 'thumbnail',
                        'width' => 75,
                        'height' => 75
                    ])->getUrl(),
                'width' => 75,
                'height' => 75,
                'alt' => $item->getName()
            ];
        }

        $totals = $this->totalsFactory->create(['data' => [
            'totals' => $items,
            'imageData' => $this->jsonHelper->jsonEncode($imageData),
            'options' => $this->jsonHelper->jsonEncode($optionsData),
            'shipping' => $shippingMethods,
            'payment' => $this->paymentMethodManagement->getList($cartId)
        ]]);

        return $totals;
    }
}
