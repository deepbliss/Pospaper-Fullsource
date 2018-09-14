<?php
namespace Magento\Sales\Model\Order\Creditmemo;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Creditmemo
 */
class Interceptor extends \Magento\Sales\Model\Order\Creditmemo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Sales\Model\Order\Creditmemo\Config $creditmemoConfig, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\Item\CollectionFactory $cmItemCollectionFactory, \Magento\Framework\Math\CalculatorFactory $calculatorFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Sales\Model\Order\Creditmemo\CommentFactory $commentFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\Comment\CollectionFactory $commentCollectionFactory, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Sales\Model\Order\InvoiceFactory $invoiceFactory = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $creditmemoConfig, $orderFactory, $cmItemCollectionFactory, $calculatorFactory, $storeManager, $commentFactory, $commentCollectionFactory, $priceCurrency, $resource, $resourceCollection, $data, $invoiceFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStore');
        if (!$pluginInfo) {
            return parent::getStore();
        } else {
            return $this->___callPlugins('getStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(\Magento\Sales\Model\Order $order)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrder');
        if (!$pluginInfo) {
            return parent::setOrder($order);
        } else {
            return $this->___callPlugins('setOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrder');
        if (!$pluginInfo) {
            return parent::getOrder();
        } else {
            return $this->___callPlugins('getOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntityType');
        if (!$pluginInfo) {
            return parent::getEntityType();
        } else {
            return $this->___callPlugins('getEntityType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBillingAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBillingAddress');
        if (!$pluginInfo) {
            return parent::getBillingAddress();
        } else {
            return $this->___callPlugins('getBillingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddress');
        if (!$pluginInfo) {
            return parent::getShippingAddress();
        } else {
            return $this->___callPlugins('getShippingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCollection');
        if (!$pluginInfo) {
            return parent::getItemsCollection();
        } else {
            return $this->___callPlugins('getItemsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllItems');
        if (!$pluginInfo) {
            return parent::getAllItems();
        } else {
            return $this->___callPlugins('getAllItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemById($itemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemById');
        if (!$pluginInfo) {
            return parent::getItemById($itemId);
        } else {
            return $this->___callPlugins('getItemById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemByOrderId($orderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemByOrderId');
        if (!$pluginInfo) {
            return parent::getItemByOrderId($orderId);
        } else {
            return $this->___callPlugins('getItemByOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(\Magento\Sales\Model\Order\Creditmemo\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addItem');
        if (!$pluginInfo) {
            return parent::addItem($item);
        } else {
            return $this->___callPlugins('addItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectTotals()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'collectTotals');
        if (!$pluginInfo) {
            return parent::collectTotals();
        } else {
            return $this->___callPlugins('collectTotals', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function roundPrice($price, $type = 'regular', $negative = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'roundPrice');
        if (!$pluginInfo) {
            return parent::roundPrice($price, $type, $negative);
        } else {
            return $this->___callPlugins('roundPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canRefund()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canRefund');
        if (!$pluginInfo) {
            return parent::canRefund();
        } else {
            return $this->___callPlugins('canRefund', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInvoice');
        if (!$pluginInfo) {
            return parent::getInvoice();
        } else {
            return $this->___callPlugins('getInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setInvoice(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setInvoice');
        if (!$pluginInfo) {
            return parent::setInvoice($invoice);
        } else {
            return $this->___callPlugins('setInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCancel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canCancel');
        if (!$pluginInfo) {
            return parent::canCancel();
        } else {
            return $this->___callPlugins('canCancel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canVoid()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canVoid');
        if (!$pluginInfo) {
            return parent::canVoid();
        } else {
            return $this->___callPlugins('canVoid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStateName($stateId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStateName');
        if (!$pluginInfo) {
            return parent::getStateName($stateId);
        } else {
            return $this->___callPlugins('getStateName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingAmount');
        if (!$pluginInfo) {
            return parent::setShippingAmount($amount);
        } else {
            return $this->___callPlugins('setShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustmentPositive($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdjustmentPositive');
        if (!$pluginInfo) {
            return parent::setAdjustmentPositive($amount);
        } else {
            return $this->___callPlugins('setAdjustmentPositive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustmentNegative($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdjustmentNegative');
        if (!$pluginInfo) {
            return parent::setAdjustmentNegative($amount);
        } else {
            return $this->___callPlugins('setAdjustmentNegative', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLast()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isLast');
        if (!$pluginInfo) {
            return parent::isLast();
        } else {
            return $this->___callPlugins('isLast', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addComment($comment, $notify = false, $visibleOnFront = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addComment');
        if (!$pluginInfo) {
            return parent::addComment($comment, $notify, $visibleOnFront);
        } else {
            return $this->___callPlugins('addComment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentsCollection($reload = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCommentsCollection');
        if (!$pluginInfo) {
            return parent::getCommentsCollection($reload);
        } else {
            return $this->___callPlugins('getCommentsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFilteredCollectionItems($filter = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFilteredCollectionItems');
        if (!$pluginInfo) {
            return parent::getFilteredCollectionItems($filter);
        } else {
            return $this->___callPlugins('getFilteredCollectionItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIncrementId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIncrementId');
        if (!$pluginInfo) {
            return parent::getIncrementId();
        } else {
            return $this->___callPlugins('getIncrementId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValidGrandTotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isValidGrandTotal');
        if (!$pluginInfo) {
            return parent::isValidGrandTotal();
        } else {
            return $this->___callPlugins('isValidGrandTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getComments()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getComments');
        if (!$pluginInfo) {
            return parent::getComments();
        } else {
            return $this->___callPlugins('getComments', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountDescription()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountDescription');
        if (!$pluginInfo) {
            return parent::getDiscountDescription();
        } else {
            return $this->___callPlugins('getDiscountDescription', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setItems($items)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItems');
        if (!$pluginInfo) {
            return parent::setItems($items);
        } else {
            return $this->___callPlugins('setItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAdjustment');
        if (!$pluginInfo) {
            return parent::getAdjustment();
        } else {
            return $this->___callPlugins('getAdjustment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentNegative()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAdjustmentNegative');
        if (!$pluginInfo) {
            return parent::getAdjustmentNegative();
        } else {
            return $this->___callPlugins('getAdjustmentNegative', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentPositive()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAdjustmentPositive');
        if (!$pluginInfo) {
            return parent::getAdjustmentPositive();
        } else {
            return $this->___callPlugins('getAdjustmentPositive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAdjustment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseAdjustment');
        if (!$pluginInfo) {
            return parent::getBaseAdjustment();
        } else {
            return $this->___callPlugins('getBaseAdjustment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAdjustmentNegative()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseAdjustmentNegative');
        if (!$pluginInfo) {
            return parent::getBaseAdjustmentNegative();
        } else {
            return $this->___callPlugins('getBaseAdjustmentNegative', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseAdjustmentNegative($baseAdjustmentNegative)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseAdjustmentNegative');
        if (!$pluginInfo) {
            return parent::setBaseAdjustmentNegative($baseAdjustmentNegative);
        } else {
            return $this->___callPlugins('setBaseAdjustmentNegative', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAdjustmentPositive()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseAdjustmentPositive');
        if (!$pluginInfo) {
            return parent::getBaseAdjustmentPositive();
        } else {
            return $this->___callPlugins('getBaseAdjustmentPositive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseAdjustmentPositive($baseAdjustmentPositive)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseAdjustmentPositive');
        if (!$pluginInfo) {
            return parent::setBaseAdjustmentPositive($baseAdjustmentPositive);
        } else {
            return $this->___callPlugins('setBaseAdjustmentPositive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseCurrencyCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseCurrencyCode');
        if (!$pluginInfo) {
            return parent::getBaseCurrencyCode();
        } else {
            return $this->___callPlugins('getBaseCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDiscountAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountAmount');
        if (!$pluginInfo) {
            return parent::getBaseDiscountAmount();
        } else {
            return $this->___callPlugins('getBaseDiscountAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseGrandTotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseGrandTotal');
        if (!$pluginInfo) {
            return parent::getBaseGrandTotal();
        } else {
            return $this->___callPlugins('getBaseGrandTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDiscountTaxCompensationAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::getBaseDiscountTaxCompensationAmount();
        } else {
            return $this->___callPlugins('getBaseDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingAmount');
        if (!$pluginInfo) {
            return parent::getBaseShippingAmount();
        } else {
            return $this->___callPlugins('getBaseShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingDiscountTaxCompensationAmnt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingDiscountTaxCompensationAmnt');
        if (!$pluginInfo) {
            return parent::getBaseShippingDiscountTaxCompensationAmnt();
        } else {
            return $this->___callPlugins('getBaseShippingDiscountTaxCompensationAmnt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingInclTax');
        if (!$pluginInfo) {
            return parent::getBaseShippingInclTax();
        } else {
            return $this->___callPlugins('getBaseShippingInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingTaxAmount');
        if (!$pluginInfo) {
            return parent::getBaseShippingTaxAmount();
        } else {
            return $this->___callPlugins('getBaseShippingTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotal');
        if (!$pluginInfo) {
            return parent::getBaseSubtotal();
        } else {
            return $this->___callPlugins('getBaseSubtotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotalInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalInclTax();
        } else {
            return $this->___callPlugins('getBaseSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTaxAmount');
        if (!$pluginInfo) {
            return parent::getBaseTaxAmount();
        } else {
            return $this->___callPlugins('getBaseTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseToGlobalRate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseToGlobalRate');
        if (!$pluginInfo) {
            return parent::getBaseToGlobalRate();
        } else {
            return $this->___callPlugins('getBaseToGlobalRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseToOrderRate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseToOrderRate');
        if (!$pluginInfo) {
            return parent::getBaseToOrderRate();
        } else {
            return $this->___callPlugins('getBaseToOrderRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBillingAddressId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBillingAddressId');
        if (!$pluginInfo) {
            return parent::getBillingAddressId();
        } else {
            return $this->___callPlugins('getBillingAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreatedAt');
        if (!$pluginInfo) {
            return parent::getCreatedAt();
        } else {
            return $this->___callPlugins('getCreatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCreatedAt');
        if (!$pluginInfo) {
            return parent::setCreatedAt($createdAt);
        } else {
            return $this->___callPlugins('setCreatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditmemoStatus()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreditmemoStatus');
        if (!$pluginInfo) {
            return parent::getCreditmemoStatus();
        } else {
            return $this->___callPlugins('getCreditmemoStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountAmount');
        if (!$pluginInfo) {
            return parent::getDiscountAmount();
        } else {
            return $this->___callPlugins('getDiscountAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailSent()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEmailSent');
        if (!$pluginInfo) {
            return parent::getEmailSent();
        } else {
            return $this->___callPlugins('getEmailSent', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobalCurrencyCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGlobalCurrencyCode');
        if (!$pluginInfo) {
            return parent::getGlobalCurrencyCode();
        } else {
            return $this->___callPlugins('getGlobalCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGrandTotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGrandTotal');
        if (!$pluginInfo) {
            return parent::getGrandTotal();
        } else {
            return $this->___callPlugins('getGrandTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountTaxCompensationAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::getDiscountTaxCompensationAmount();
        } else {
            return $this->___callPlugins('getDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoiceId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInvoiceId');
        if (!$pluginInfo) {
            return parent::getInvoiceId();
        } else {
            return $this->___callPlugins('getInvoiceId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderCurrencyCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrderCurrencyCode');
        if (!$pluginInfo) {
            return parent::getOrderCurrencyCode();
        } else {
            return $this->___callPlugins('getOrderCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrderId');
        if (!$pluginInfo) {
            return parent::getOrderId();
        } else {
            return $this->___callPlugins('getOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddressId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAddressId');
        if (!$pluginInfo) {
            return parent::getShippingAddressId();
        } else {
            return $this->___callPlugins('getShippingAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingAmount');
        if (!$pluginInfo) {
            return parent::getShippingAmount();
        } else {
            return $this->___callPlugins('getShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingDiscountTaxCompensationAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::getShippingDiscountTaxCompensationAmount();
        } else {
            return $this->___callPlugins('getShippingDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingInclTax');
        if (!$pluginInfo) {
            return parent::getShippingInclTax();
        } else {
            return $this->___callPlugins('getShippingInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingTaxAmount');
        if (!$pluginInfo) {
            return parent::getShippingTaxAmount();
        } else {
            return $this->___callPlugins('getShippingTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getState()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getState');
        if (!$pluginInfo) {
            return parent::getState();
        } else {
            return $this->___callPlugins('getState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreCurrencyCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreCurrencyCode');
        if (!$pluginInfo) {
            return parent::getStoreCurrencyCode();
        } else {
            return $this->___callPlugins('getStoreCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreId');
        if (!$pluginInfo) {
            return parent::getStoreId();
        } else {
            return $this->___callPlugins('getStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreToBaseRate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreToBaseRate');
        if (!$pluginInfo) {
            return parent::getStoreToBaseRate();
        } else {
            return $this->___callPlugins('getStoreToBaseRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreToOrderRate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreToOrderRate');
        if (!$pluginInfo) {
            return parent::getStoreToOrderRate();
        } else {
            return $this->___callPlugins('getStoreToOrderRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotal');
        if (!$pluginInfo) {
            return parent::getSubtotal();
        } else {
            return $this->___callPlugins('getSubtotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotalInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::getSubtotalInclTax();
        } else {
            return $this->___callPlugins('getSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxAmount');
        if (!$pluginInfo) {
            return parent::getTaxAmount();
        } else {
            return $this->___callPlugins('getTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTransactionId');
        if (!$pluginInfo) {
            return parent::getTransactionId();
        } else {
            return $this->___callPlugins('getTransactionId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTransactionId($transactionId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTransactionId');
        if (!$pluginInfo) {
            return parent::setTransactionId($transactionId);
        } else {
            return $this->___callPlugins('setTransactionId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUpdatedAt');
        if (!$pluginInfo) {
            return parent::getUpdatedAt();
        } else {
            return $this->___callPlugins('getUpdatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setComments($comments)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setComments');
        if (!$pluginInfo) {
            return parent::setComments($comments);
        } else {
            return $this->___callPlugins('setComments', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreId');
        if (!$pluginInfo) {
            return parent::setStoreId($id);
        } else {
            return $this->___callPlugins('setStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingTaxAmount');
        if (!$pluginInfo) {
            return parent::setBaseShippingTaxAmount($amount);
        } else {
            return $this->___callPlugins('setBaseShippingTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreToOrderRate($rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreToOrderRate');
        if (!$pluginInfo) {
            return parent::setStoreToOrderRate($rate);
        } else {
            return $this->___callPlugins('setStoreToOrderRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountAmount');
        if (!$pluginInfo) {
            return parent::setBaseDiscountAmount($amount);
        } else {
            return $this->___callPlugins('setBaseDiscountAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseToOrderRate($rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseToOrderRate');
        if (!$pluginInfo) {
            return parent::setBaseToOrderRate($rate);
        } else {
            return $this->___callPlugins('setBaseToOrderRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGrandTotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGrandTotal');
        if (!$pluginInfo) {
            return parent::setGrandTotal($amount);
        } else {
            return $this->___callPlugins('setGrandTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseSubtotalInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::setBaseSubtotalInclTax($amount);
        } else {
            return $this->___callPlugins('setBaseSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotalInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubtotalInclTax');
        if (!$pluginInfo) {
            return parent::setSubtotalInclTax($amount);
        } else {
            return $this->___callPlugins('setSubtotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingAmount');
        if (!$pluginInfo) {
            return parent::setBaseShippingAmount($amount);
        } else {
            return $this->___callPlugins('setBaseShippingAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreToBaseRate($rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreToBaseRate');
        if (!$pluginInfo) {
            return parent::setStoreToBaseRate($rate);
        } else {
            return $this->___callPlugins('setStoreToBaseRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseToGlobalRate($rate)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseToGlobalRate');
        if (!$pluginInfo) {
            return parent::setBaseToGlobalRate($rate);
        } else {
            return $this->___callPlugins('setBaseToGlobalRate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseAdjustment($baseAdjustment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseAdjustment');
        if (!$pluginInfo) {
            return parent::setBaseAdjustment($baseAdjustment);
        } else {
            return $this->___callPlugins('setBaseAdjustment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseSubtotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseSubtotal');
        if (!$pluginInfo) {
            return parent::setBaseSubtotal($amount);
        } else {
            return $this->___callPlugins('setBaseSubtotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountAmount');
        if (!$pluginInfo) {
            return parent::setDiscountAmount($amount);
        } else {
            return $this->___callPlugins('setDiscountAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubtotal');
        if (!$pluginInfo) {
            return parent::setSubtotal($amount);
        } else {
            return $this->___callPlugins('setSubtotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustment($adjustment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdjustment');
        if (!$pluginInfo) {
            return parent::setAdjustment($adjustment);
        } else {
            return $this->___callPlugins('setAdjustment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseGrandTotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseGrandTotal');
        if (!$pluginInfo) {
            return parent::setBaseGrandTotal($amount);
        } else {
            return $this->___callPlugins('setBaseGrandTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTaxAmount');
        if (!$pluginInfo) {
            return parent::setBaseTaxAmount($amount);
        } else {
            return $this->___callPlugins('setBaseTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingTaxAmount');
        if (!$pluginInfo) {
            return parent::setShippingTaxAmount($amount);
        } else {
            return $this->___callPlugins('setShippingTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxAmount');
        if (!$pluginInfo) {
            return parent::setTaxAmount($amount);
        } else {
            return $this->___callPlugins('setTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrderId');
        if (!$pluginInfo) {
            return parent::setOrderId($id);
        } else {
            return $this->___callPlugins('setOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailSent($emailSent)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEmailSent');
        if (!$pluginInfo) {
            return parent::setEmailSent($emailSent);
        } else {
            return $this->___callPlugins('setEmailSent', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCreditmemoStatus($creditmemoStatus)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCreditmemoStatus');
        if (!$pluginInfo) {
            return parent::setCreditmemoStatus($creditmemoStatus);
        } else {
            return $this->___callPlugins('setCreditmemoStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setState($state)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setState');
        if (!$pluginInfo) {
            return parent::setState($state);
        } else {
            return $this->___callPlugins('setState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddressId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingAddressId');
        if (!$pluginInfo) {
            return parent::setShippingAddressId($id);
        } else {
            return $this->___callPlugins('setShippingAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddressId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBillingAddressId');
        if (!$pluginInfo) {
            return parent::setBillingAddressId($id);
        } else {
            return $this->___callPlugins('setBillingAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setInvoiceId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setInvoiceId');
        if (!$pluginInfo) {
            return parent::setInvoiceId($id);
        } else {
            return $this->___callPlugins('setInvoiceId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreCurrencyCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreCurrencyCode');
        if (!$pluginInfo) {
            return parent::setStoreCurrencyCode($code);
        } else {
            return $this->___callPlugins('setStoreCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrderCurrencyCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrderCurrencyCode');
        if (!$pluginInfo) {
            return parent::setOrderCurrencyCode($code);
        } else {
            return $this->___callPlugins('setOrderCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseCurrencyCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseCurrencyCode');
        if (!$pluginInfo) {
            return parent::setBaseCurrencyCode($code);
        } else {
            return $this->___callPlugins('setBaseCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGlobalCurrencyCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGlobalCurrencyCode');
        if (!$pluginInfo) {
            return parent::setGlobalCurrencyCode($code);
        } else {
            return $this->___callPlugins('setGlobalCurrencyCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIncrementId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIncrementId');
        if (!$pluginInfo) {
            return parent::setIncrementId($id);
        } else {
            return $this->___callPlugins('setIncrementId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($timestamp)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setUpdatedAt');
        if (!$pluginInfo) {
            return parent::setUpdatedAt($timestamp);
        } else {
            return $this->___callPlugins('setUpdatedAt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountTaxCompensationAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::setDiscountTaxCompensationAmount($amount);
        } else {
            return $this->___callPlugins('setDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountTaxCompensationAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::setBaseDiscountTaxCompensationAmount($amount);
        } else {
            return $this->___callPlugins('setBaseDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingDiscountTaxCompensationAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingDiscountTaxCompensationAmount');
        if (!$pluginInfo) {
            return parent::setShippingDiscountTaxCompensationAmount($amount);
        } else {
            return $this->___callPlugins('setShippingDiscountTaxCompensationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingDiscountTaxCompensationAmnt($amnt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingDiscountTaxCompensationAmnt');
        if (!$pluginInfo) {
            return parent::setBaseShippingDiscountTaxCompensationAmnt($amnt);
        } else {
            return $this->___callPlugins('setBaseShippingDiscountTaxCompensationAmnt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingInclTax');
        if (!$pluginInfo) {
            return parent::setShippingInclTax($amount);
        } else {
            return $this->___callPlugins('setShippingInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingInclTax');
        if (!$pluginInfo) {
            return parent::setBaseShippingInclTax($amount);
        } else {
            return $this->___callPlugins('setBaseShippingInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountDescription($description)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountDescription');
        if (!$pluginInfo) {
            return parent::setDiscountDescription($description);
        } else {
            return $this->___callPlugins('setDiscountDescription', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExtensionAttributes');
        if (!$pluginInfo) {
            return parent::getExtensionAttributes();
        } else {
            return $this->___callPlugins('getExtensionAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(\Magento\Sales\Api\Data\CreditmemoExtensionInterface $extensionAttributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setExtensionAttributes');
        if (!$pluginInfo) {
            return parent::setExtensionAttributes($extensionAttributes);
        } else {
            return $this->___callPlugins('setExtensionAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEventObject()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEventObject');
        if (!$pluginInfo) {
            return parent::getEventObject();
        } else {
            return $this->___callPlugins('getEventObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomAttributes()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomAttributes');
        if (!$pluginInfo) {
            return parent::getCustomAttributes();
        } else {
            return $this->___callPlugins('getCustomAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomAttribute($attributeCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomAttribute');
        if (!$pluginInfo) {
            return parent::getCustomAttribute($attributeCode);
        } else {
            return $this->___callPlugins('getCustomAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomAttributes(array $attributes)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomAttributes');
        if (!$pluginInfo) {
            return parent::setCustomAttributes($attributes);
        } else {
            return $this->___callPlugins('setCustomAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomAttribute($attributeCode, $attributeValue)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomAttribute');
        if (!$pluginInfo) {
            return parent::setCustomAttribute($attributeCode, $attributeValue);
        } else {
            return $this->___callPlugins('setCustomAttribute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setData');
        if (!$pluginInfo) {
            return parent::setData($key, $value);
        } else {
            return $this->___callPlugins('setData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unsetData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unsetData');
        if (!$pluginInfo) {
            return parent::unsetData($key);
        } else {
            return $this->___callPlugins('unsetData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setId($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setId');
        if (!$pluginInfo) {
            return parent::setId($value);
        } else {
            return $this->___callPlugins('setId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIdFieldName($name)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIdFieldName');
        if (!$pluginInfo) {
            return parent::setIdFieldName($name);
        } else {
            return $this->___callPlugins('setIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdFieldName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIdFieldName');
        if (!$pluginInfo) {
            return parent::getIdFieldName();
        } else {
            return $this->___callPlugins('getIdFieldName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getId');
        if (!$pluginInfo) {
            return parent::getId();
        } else {
            return $this->___callPlugins('getId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDeleted($isDeleted = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDeleted');
        if (!$pluginInfo) {
            return parent::isDeleted($isDeleted);
        } else {
            return $this->___callPlugins('isDeleted', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasDataChanges()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasDataChanges');
        if (!$pluginInfo) {
            return parent::hasDataChanges();
        } else {
            return $this->___callPlugins('hasDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataChanges($value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataChanges');
        if (!$pluginInfo) {
            return parent::setDataChanges($value);
        } else {
            return $this->___callPlugins('setDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigData($key = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrigData');
        if (!$pluginInfo) {
            return parent::getOrigData($key);
        } else {
            return $this->___callPlugins('getOrigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOrigData($key = null, $data = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOrigData');
        if (!$pluginInfo) {
            return parent::setOrigData($key, $data);
        } else {
            return $this->___callPlugins('setOrigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dataHasChangedFor($field)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dataHasChangedFor');
        if (!$pluginInfo) {
            return parent::dataHasChangedFor($field);
        } else {
            return $this->___callPlugins('dataHasChangedFor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceName');
        if (!$pluginInfo) {
            return parent::getResourceName();
        } else {
            return $this->___callPlugins('getResourceName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResourceCollection');
        if (!$pluginInfo) {
            return parent::getResourceCollection();
        } else {
            return $this->___callPlugins('getResourceCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCollection');
        if (!$pluginInfo) {
            return parent::getCollection();
        } else {
            return $this->___callPlugins('getCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load($modelId, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($modelId, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad($identifier, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeLoad');
        if (!$pluginInfo) {
            return parent::beforeLoad($identifier, $field);
        } else {
            return $this->___callPlugins('beforeLoad', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterLoad');
        if (!$pluginInfo) {
            return parent::afterLoad();
        } else {
            return $this->___callPlugins('afterLoad', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSaveAllowed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isSaveAllowed');
        if (!$pluginInfo) {
            return parent::isSaveAllowed();
        } else {
            return $this->___callPlugins('isSaveAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHasDataChanges($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHasDataChanges');
        if (!$pluginInfo) {
            return parent::setHasDataChanges($flag);
        } else {
            return $this->___callPlugins('setHasDataChanges', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterCommitCallback()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterCommitCallback');
        if (!$pluginInfo) {
            return parent::afterCommitCallback();
        } else {
            return $this->___callPlugins('afterCommitCallback', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isObjectNew($flag = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isObjectNew');
        if (!$pluginInfo) {
            return parent::isObjectNew($flag);
        } else {
            return $this->___callPlugins('isObjectNew', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeSave');
        if (!$pluginInfo) {
            return parent::beforeSave();
        } else {
            return $this->___callPlugins('beforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateBeforeSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validateBeforeSave');
        if (!$pluginInfo) {
            return parent::validateBeforeSave();
        } else {
            return $this->___callPlugins('validateBeforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheTags()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCacheTags');
        if (!$pluginInfo) {
            return parent::getCacheTags();
        } else {
            return $this->___callPlugins('getCacheTags', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cleanModelCache()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cleanModelCache');
        if (!$pluginInfo) {
            return parent::cleanModelCache();
        } else {
            return $this->___callPlugins('cleanModelCache', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave();
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeDelete');
        if (!$pluginInfo) {
            return parent::beforeDelete();
        } else {
            return $this->___callPlugins('beforeDelete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterDelete');
        if (!$pluginInfo) {
            return parent::afterDelete();
        } else {
            return $this->___callPlugins('afterDelete', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterDeleteCommit()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterDeleteCommit');
        if (!$pluginInfo) {
            return parent::afterDeleteCommit();
        } else {
            return $this->___callPlugins('afterDeleteCommit', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResource');
        if (!$pluginInfo) {
            return parent::getResource();
        } else {
            return $this->___callPlugins('getResource', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEntityId');
        if (!$pluginInfo) {
            return parent::getEntityId();
        } else {
            return $this->___callPlugins('getEntityId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId($entityId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEntityId');
        if (!$pluginInfo) {
            return parent::setEntityId($entityId);
        } else {
            return $this->___callPlugins('setEntityId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearInstance()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearInstance');
        if (!$pluginInfo) {
            return parent::clearInstance();
        } else {
            return $this->___callPlugins('clearInstance', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoredData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoredData');
        if (!$pluginInfo) {
            return parent::getStoredData();
        } else {
            return $this->___callPlugins('getStoredData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEventPrefix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEventPrefix');
        if (!$pluginInfo) {
            return parent::getEventPrefix();
        } else {
            return $this->___callPlugins('getEventPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addData(array $arr)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addData');
        if (!$pluginInfo) {
            return parent::addData($arr);
        } else {
            return $this->___callPlugins('addData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByPath($path)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByPath');
        if (!$pluginInfo) {
            return parent::getDataByPath($path);
        } else {
            return $this->___callPlugins('getDataByPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataByKey($key)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataByKey');
        if (!$pluginInfo) {
            return parent::getDataByKey($key);
        } else {
            return $this->___callPlugins('getDataByKey', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDataUsingMethod($key, $args = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDataUsingMethod');
        if (!$pluginInfo) {
            return parent::setDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('setDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDataUsingMethod');
        if (!$pluginInfo) {
            return parent::getDataUsingMethod($key, $args);
        } else {
            return $this->___callPlugins('getDataUsingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasData($key = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasData');
        if (!$pluginInfo) {
            return parent::hasData($key);
        } else {
            return $this->___callPlugins('hasData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toArray');
        if (!$pluginInfo) {
            return parent::toArray($keys);
        } else {
            return $this->___callPlugins('toArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToArray(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToArray');
        if (!$pluginInfo) {
            return parent::convertToArray($keys);
        } else {
            return $this->___callPlugins('convertToArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toXml(array $keys = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toXml');
        if (!$pluginInfo) {
            return parent::toXml($keys, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('toXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToXml');
        if (!$pluginInfo) {
            return parent::convertToXml($arrAttributes, $rootName, $addOpenTag, $addCdata);
        } else {
            return $this->___callPlugins('convertToXml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toJson');
        if (!$pluginInfo) {
            return parent::toJson($keys);
        } else {
            return $this->___callPlugins('toJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertToJson(array $keys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convertToJson');
        if (!$pluginInfo) {
            return parent::convertToJson($keys);
        } else {
            return $this->___callPlugins('convertToJson', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString($format = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toString');
        if (!$pluginInfo) {
            return parent::toString($format);
        } else {
            return $this->___callPlugins('toString', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $args)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, '__call');
        if (!$pluginInfo) {
            return parent::__call($method, $args);
        } else {
            return $this->___callPlugins('__call', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isEmpty');
        if (!$pluginInfo) {
            return parent::isEmpty();
        } else {
            return $this->___callPlugins('isEmpty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($keys = array(), $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'serialize');
        if (!$pluginInfo) {
            return parent::serialize($keys, $valueSeparator, $fieldSeparator, $quote);
        } else {
            return $this->___callPlugins('serialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function debug($data = null, &$objects = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'debug');
        if (!$pluginInfo) {
            return parent::debug($data, $objects);
        } else {
            return $this->___callPlugins('debug', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetSet');
        if (!$pluginInfo) {
            return parent::offsetSet($offset, $value);
        } else {
            return $this->___callPlugins('offsetSet', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetExists');
        if (!$pluginInfo) {
            return parent::offsetExists($offset);
        } else {
            return $this->___callPlugins('offsetExists', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetUnset');
        if (!$pluginInfo) {
            return parent::offsetUnset($offset);
        } else {
            return $this->___callPlugins('offsetUnset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'offsetGet');
        if (!$pluginInfo) {
            return parent::offsetGet($offset);
        } else {
            return $this->___callPlugins('offsetGet', func_get_args(), $pluginInfo);
        }
    }
}
