<?php

namespace Pos\Custommodule\Plugin\Model\Sales;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderExtension;
use Magento\Framework\App\State;

class Order
{
    private $orderExtensionFactory;

    private $state;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        State $state
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->state = $state;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderCollection $resultOrder
     * @return OrderCollection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderCollection $resultOrder)
    {
        foreach ($resultOrder->getItems() as $order) {
            $this->afterGet($subject, $order);
        }
        return $resultOrder;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $resultOrder)
    {
        if(strpos($this->state->getAreaCode(),'webapi_') === false) {
            return $resultOrder;
        }

        $extensionAttributes = $resultOrder->getExtensionAttributes();
        if ($this->isDataAlreadySet($extensionAttributes)) {
            return $resultOrder;
        }

        /** @var OrderExtension $orderExtension */
        $orderExtension = $extensionAttributes
            ? $extensionAttributes
            : $this->orderExtensionFactory->create();
        $this->setCustomerNote($resultOrder, $orderExtension);
        $resultOrder->setExtensionAttributes($orderExtension);

        return $resultOrder;
    }

    /**
     * Is points data already set
     *
     * @param OrderExtensionInterface $extensionAttributes
     * @return bool
     */
    private function isDataAlreadySet($extensionAttributes)
    {
        return $extensionAttributes && $extensionAttributes->getCustomerNote();
    }

    /**
     * Set points data
     *
     * @param OrderInterface $resultOrder
     * @param OrderExtension $orderExtension
     * @return void
     */
    private function setCustomerNote(OrderInterface $resultOrder, OrderExtension $orderExtension)
    {
        $text = '';
        $comment = $resultOrder->getStatusHistoryCollection()
            ->getLastItem();
        if($comment) {
            if(!$comment->getIsVisibleOnFront()) {
                $text = '';
            } else {
                if(!$comment->getComment()) {
                    $text = '';
                } else {
                    $text = $comment->getComment();
                }
            }
        }

        $orderExtension->setCustomerNote($text);
    }
}
