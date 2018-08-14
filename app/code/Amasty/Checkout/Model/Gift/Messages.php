<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Model\Gift;

class Messages
{
    const QUOTE_MESSAGE_INDEX = 0;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;
    /**
     * @var \Magento\GiftMessage\Helper\Message
     */
    protected $messageHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\GiftMessage\Model\ResourceModel\Message\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\GiftMessage\Api\CartRepositoryInterface
     */
    protected $cartRepository;
    /**
     * @var \Magento\GiftMessage\Api\ItemRepositoryInterface
     */
    protected $itemRepository;
    /**
     * @var \Magento\GiftMessage\Model\MessageFactory
     */
    protected $messageFactory;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\GiftMessage\Helper\Message $messageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\GiftMessage\Model\ResourceModel\Message\CollectionFactory $collectionFactory,

        \Magento\GiftMessage\Api\CartRepositoryInterface $cartRepository,
        \Magento\GiftMessage\Api\ItemRepositoryInterface $itemRepository,
        \Magento\GiftMessage\Model\MessageFactory $messageFactory
    ) {

        $this->checkoutSession = $checkoutSession;
        $this->messageHelper = $messageHelper;
        $this->storeManager = $storeManager;
        $this->collectionFactory = $collectionFactory;
        $this->cartRepository = $cartRepository;
        $this->itemRepository = $itemRepository;
        $this->messageFactory = $messageFactory;
    }

    public function getGiftMessages()
    {
        $quote = $this->checkoutSession->getQuote();

        if ($quote->isVirtual())
            return false;

        if (0 == $quote->getItemsCount())
            return false;

        $messages = [];

        if ($this->messageHelper->isMessagesAllowed('quote', $quote, $this->storeManager->getStore())) {
            $messages[self::QUOTE_MESSAGE_INDEX] = $quote->getGiftMessageId();
        }

        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getIsVirtual())
                continue;

            if (!$this->messageHelper->isMessagesAllowed('order_item', $quote, $this->storeManager->getStore()))
                continue;

            $messages[$item->getId()] = $item->getGiftMessageId();
        }

        /** @var \Magento\GiftMessage\Model\ResourceModel\Message\Collection $messageCollection */
        $messageCollection = $this->collectionFactory->create();
        $messageCollection->addFieldToFilter('gift_message_id', ['in' => $messages]);

        foreach ($messages as $i => $id) {
            $message = $messageCollection->getItemById($id);
            if (!$message) {
                $message = new \Magento\Framework\DataObject(['item_id' => $id]);
            }

            if ($i != self::QUOTE_MESSAGE_INDEX) {
                $for = $quote->getItemById($i)->getName();
            }
            else {
                $for = __('Whole Order');
            }

            $title = __('Gift Message for %1 (optional)', $for);

            $message->setData('title', $title);

            $messages[$i] = $message;
        }

        return $messages;
    }

    public function clearGiftMessages()
    {
        $quote = $this->checkoutSession->getQuote();

        $emptyMessage = $this->messageFactory->create();

        if ($quote->getGiftMessageId()) {
            $this->cartRepository->save($quote->getId(), $emptyMessage);
        }

        foreach ($quote->getAllVisibleItems() as $item) {
            if ($item->getGiftMessageId()) {
                $this->cartRepository->save($quote->getId(), $emptyMessage);
            }
        }
    }
}
