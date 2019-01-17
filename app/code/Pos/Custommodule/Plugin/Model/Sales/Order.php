<?php

namespace Pos\Custommodule\Plugin\Model\Sales;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Swissup\CheckoutFields\Model\ResourceModel\Field\Value\CollectionFactory as FieldValueCollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderExtension;
use Pos\Custommodule\Model\CustomOptionsFactory;
use Magento\Framework\Phrase;
use Magento\Framework\App\State;

class Order
{
    protected $orderExtensionFactory;
    protected $orderItemExtensionFactory;
    protected $state;
    protected $customOptionsFactory;
    public $fieldValueCollectionFactory;

    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        OrderItemExtensionFactory $orderItemExtensionFactory,
        FieldValueCollectionFactory $fieldValueCollectionFactory,
        State $state,
        CustomOptionsFactory $customOptionsFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->fieldValueCollectionFactory = $fieldValueCollectionFactory;
        $this->state = $state;
        $this->customOptionsFactory = $customOptionsFactory;
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

        $comments = $this->getCustomerNotes($resultOrder);

        $orderItems = $resultOrder->getItems();
        if (null !== $orderItems) {
            foreach ($orderItems as $orderItem) {
                $extensionAttributes = $orderItem->getExtensionAttributes();
                if ($extensionAttributes && $extensionAttributes->getCustomOptions()) {
                    continue;
                }
                $customOptions = array();
                $productOptions = $orderItem->getProductOptions();
                if(is_array($productOptions) && isset($productOptions['options'])) {
                    $options = $productOptions['options'];
                    if(is_array($options)) {
                        foreach ($options as $option) {
                            $customOptions[] = $option['label'].': '.$option['value'];
                        }
                    }
                }
                if(!empty($customOptions)) {
                    $customOptions = implode(',',$customOptions);
                    if($comments != '') {
                        $comments .= '; ';
                    }
                    $comments .= $customOptions;
                }
            }
        }

        $this->setCustomerNote($resultOrder, $orderExtension,$comments);
        $resultOrder->setExtensionAttributes($orderExtension);

        return $resultOrder;
    }

    private function isDataAlreadySet($extensionAttributes)
    {
        return $extensionAttributes && $extensionAttributes->getCustomerNote();
    }

    private function getCustomerNotes($resultOrder)
    {
        $text = '';
        /*
        $comments = $resultOrder->getStatusHistoryCollection();
        foreach ($comments as $comment) {
            if($comment) {
                if($comment->getIsVisibleOnFront()) {
                    if($comment->getComment()) {
                        $text[] = $comment->getComment();
                    }
                }
            }
        }
        */
        $storeId = $resultOrder->getStore()->getId();

        $fields = $this->fieldValueCollectionFactory
            ->create()
            ->addEmptyValueFilter()
            ->addOrderFilter($resultOrder->getId())
            ->addStoreLabel($storeId);

        foreach($fields as $field) {
            if(strpos(strtolower($field->getStoreLabel()),'comment') !== false) {
                $text = $field->getValue();
            }
        }

        return $text;
    }

    private function setCustomerNote(OrderInterface $resultOrder, OrderExtension $orderExtension, $comments)
    {
        $orderExtension->setCustomerNote($comments);
    }
}
