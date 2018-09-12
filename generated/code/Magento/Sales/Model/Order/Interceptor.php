<?php
namespace Magento\Sales\Model\Order;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order
 */
class Interceptor extends \Magento\Sales\Model\Order implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Sales\Model\Order\Config $orderConfig, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory, \Magento\Catalog\Model\Product\Visibility $productVisibility, \Magento\Sales\Api\InvoiceManagementInterface $invoiceManagement, \Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Eav\Model\Config $eavConfig, \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory, \Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory $addressCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $paymentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Status\History\CollectionFactory $historyCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $memoCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory $trackCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollectionFactory, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productListFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Framework\Locale\ResolverInterface $localeResolver = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $timezone, $storeManager, $orderConfig, $productRepository, $orderItemCollectionFactory, $productVisibility, $invoiceManagement, $currencyFactory, $eavConfig, $orderHistoryFactory, $addressCollectionFactory, $paymentCollectionFactory, $historyCollectionFactory, $invoiceCollectionFactory, $shipmentCollectionFactory, $memoCollectionFactory, $trackCollectionFactory, $salesOrderCollectionFactory, $priceCurrency, $productListFactory, $resource, $resourceCollection, $data, $localeResolver);
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
    public function getActionFlag($action)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag($action);
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setActionFlag($action, $flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setActionFlag');
        if (!$pluginInfo) {
            return parent::setActionFlag($action, $flag);
        } else {
            return $this->___callPlugins('setActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCanSendNewEmailFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCanSendNewEmailFlag');
        if (!$pluginInfo) {
            return parent::getCanSendNewEmailFlag();
        } else {
            return $this->___callPlugins('getCanSendNewEmailFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCanSendNewEmailFlag($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCanSendNewEmailFlag');
        if (!$pluginInfo) {
            return parent::setCanSendNewEmailFlag($flag);
        } else {
            return $this->___callPlugins('setCanSendNewEmailFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadByIncrementId($incrementId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByIncrementId');
        if (!$pluginInfo) {
            return parent::loadByIncrementId($incrementId);
        } else {
            return $this->___callPlugins('loadByIncrementId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadByIncrementIdAndStoreId($incrementId, $storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByIncrementIdAndStoreId');
        if (!$pluginInfo) {
            return parent::loadByIncrementIdAndStoreId($incrementId, $storeId);
        } else {
            return $this->___callPlugins('loadByIncrementIdAndStoreId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadByAttribute($attribute, $value)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'loadByAttribute');
        if (!$pluginInfo) {
            return parent::loadByAttribute($attribute, $value);
        } else {
            return $this->___callPlugins('loadByAttribute', func_get_args(), $pluginInfo);
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
    public function canVoidPayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canVoidPayment');
        if (!$pluginInfo) {
            return parent::canVoidPayment();
        } else {
            return $this->___callPlugins('canVoidPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canInvoice');
        if (!$pluginInfo) {
            return parent::canInvoice();
        } else {
            return $this->___callPlugins('canInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCreditmemo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canCreditmemo');
        if (!$pluginInfo) {
            return parent::canCreditmemo();
        } else {
            return $this->___callPlugins('canCreditmemo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canHold()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canHold');
        if (!$pluginInfo) {
            return parent::canHold();
        } else {
            return $this->___callPlugins('canHold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUnhold()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canUnhold');
        if (!$pluginInfo) {
            return parent::canUnhold();
        } else {
            return $this->___callPlugins('canUnhold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canComment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canComment');
        if (!$pluginInfo) {
            return parent::canComment();
        } else {
            return $this->___callPlugins('canComment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canShip()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canShip');
        if (!$pluginInfo) {
            return parent::canShip();
        } else {
            return $this->___callPlugins('canShip', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canEdit()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canEdit');
        if (!$pluginInfo) {
            return parent::canEdit();
        } else {
            return $this->___callPlugins('canEdit', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canReorder()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canReorder');
        if (!$pluginInfo) {
            return parent::canReorder();
        } else {
            return $this->___callPlugins('canReorder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canReorderIgnoreSalable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canReorderIgnoreSalable');
        if (!$pluginInfo) {
            return parent::canReorderIgnoreSalable();
        } else {
            return $this->___callPlugins('canReorderIgnoreSalable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPaymentReview()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isPaymentReview');
        if (!$pluginInfo) {
            return parent::isPaymentReview();
        } else {
            return $this->___callPlugins('isPaymentReview', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canReviewPayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canReviewPayment');
        if (!$pluginInfo) {
            return parent::canReviewPayment();
        } else {
            return $this->___callPlugins('canReviewPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canFetchPaymentReviewUpdate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canFetchPaymentReviewUpdate');
        if (!$pluginInfo) {
            return parent::canFetchPaymentReviewUpdate();
        } else {
            return $this->___callPlugins('canFetchPaymentReviewUpdate', func_get_args(), $pluginInfo);
        }
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
    public function getPayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPayment');
        if (!$pluginInfo) {
            return parent::getPayment();
        } else {
            return $this->___callPlugins('getPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddress(\Magento\Sales\Api\Data\OrderAddressInterface $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBillingAddress');
        if (!$pluginInfo) {
            return parent::setBillingAddress($address);
        } else {
            return $this->___callPlugins('setBillingAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(\Magento\Sales\Api\Data\OrderAddressInterface $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingAddress');
        if (!$pluginInfo) {
            return parent::setShippingAddress($address);
        } else {
            return $this->___callPlugins('setShippingAddress', func_get_args(), $pluginInfo);
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
    public function getStatusLabel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusLabel');
        if (!$pluginInfo) {
            return parent::getStatusLabel();
        } else {
            return $this->___callPlugins('getStatusLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addStatusToHistory($status, $comment = '', $isCustomerNotified = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addStatusToHistory');
        if (!$pluginInfo) {
            return parent::addStatusToHistory($status, $comment, $isCustomerNotified);
        } else {
            return $this->___callPlugins('addStatusToHistory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addStatusHistoryComment($comment, $status = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addStatusHistoryComment');
        if (!$pluginInfo) {
            return parent::addStatusHistoryComment($comment, $status);
        } else {
            return $this->___callPlugins('addStatusHistoryComment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHistoryEntityName($entityName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHistoryEntityName');
        if (!$pluginInfo) {
            return parent::setHistoryEntityName($entityName);
        } else {
            return $this->___callPlugins('setHistoryEntityName', func_get_args(), $pluginInfo);
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
    public function place()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'place');
        if (!$pluginInfo) {
            return parent::place();
        } else {
            return $this->___callPlugins('place', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hold()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hold');
        if (!$pluginInfo) {
            return parent::hold();
        } else {
            return $this->___callPlugins('hold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unhold()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unhold');
        if (!$pluginInfo) {
            return parent::unhold();
        } else {
            return $this->___callPlugins('unhold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cancel');
        if (!$pluginInfo) {
            return parent::cancel();
        } else {
            return $this->___callPlugins('cancel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isFraudDetected()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isFraudDetected');
        if (!$pluginInfo) {
            return parent::isFraudDetected();
        } else {
            return $this->___callPlugins('isFraudDetected', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerCancellation($comment = '', $graceful = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'registerCancellation');
        if (!$pluginInfo) {
            return parent::registerCancellation($comment, $graceful);
        } else {
            return $this->___callPlugins('registerCancellation', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTrackingNumbers()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTrackingNumbers');
        if (!$pluginInfo) {
            return parent::getTrackingNumbers();
        } else {
            return $this->___callPlugins('getTrackingNumbers', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethod($asObject = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingMethod');
        if (!$pluginInfo) {
            return parent::getShippingMethod($asObject);
        } else {
            return $this->___callPlugins('getShippingMethod', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressesCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressesCollection');
        if (!$pluginInfo) {
            return parent::getAddressesCollection();
        } else {
            return $this->___callPlugins('getAddressesCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressById($addressId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressById');
        if (!$pluginInfo) {
            return parent::getAddressById($addressId);
        } else {
            return $this->___callPlugins('getAddressById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(\Magento\Sales\Model\Order\Address $address)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAddress');
        if (!$pluginInfo) {
            return parent::addAddress($address);
        } else {
            return $this->___callPlugins('addAddress', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsCollection($filterByTypes = array(), $nonChildrenOnly = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsCollection');
        if (!$pluginInfo) {
            return parent::getItemsCollection($filterByTypes, $nonChildrenOnly);
        } else {
            return $this->___callPlugins('getItemsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParentItemsRandomCollection($limit = 1)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getParentItemsRandomCollection');
        if (!$pluginInfo) {
            return parent::getParentItemsRandomCollection($limit);
        } else {
            return $this->___callPlugins('getParentItemsRandomCollection', func_get_args(), $pluginInfo);
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
    public function getAllVisibleItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllVisibleItems');
        if (!$pluginInfo) {
            return parent::getAllVisibleItems();
        } else {
            return $this->___callPlugins('getAllVisibleItems', func_get_args(), $pluginInfo);
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
    public function getItemByQuoteItemId($quoteItemId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemByQuoteItemId');
        if (!$pluginInfo) {
            return parent::getItemByQuoteItemId($quoteItemId);
        } else {
            return $this->___callPlugins('getItemByQuoteItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(\Magento\Sales\Model\Order\Item $item)
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
    public function getPaymentsCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentsCollection');
        if (!$pluginInfo) {
            return parent::getPaymentsCollection();
        } else {
            return $this->___callPlugins('getPaymentsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllPayments()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllPayments');
        if (!$pluginInfo) {
            return parent::getAllPayments();
        } else {
            return $this->___callPlugins('getAllPayments', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentById($paymentId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentById');
        if (!$pluginInfo) {
            return parent::getPaymentById($paymentId);
        } else {
            return $this->___callPlugins('getPaymentById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPayment(\Magento\Sales\Api\Data\OrderPaymentInterface $payment = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPayment');
        if (!$pluginInfo) {
            return parent::setPayment($payment);
        } else {
            return $this->___callPlugins('setPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusHistoryCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusHistoryCollection');
        if (!$pluginInfo) {
            return parent::getStatusHistoryCollection();
        } else {
            return $this->___callPlugins('getStatusHistoryCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAllStatusHistory()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAllStatusHistory');
        if (!$pluginInfo) {
            return parent::getAllStatusHistory();
        } else {
            return $this->___callPlugins('getAllStatusHistory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibleStatusHistory()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getVisibleStatusHistory');
        if (!$pluginInfo) {
            return parent::getVisibleStatusHistory();
        } else {
            return $this->___callPlugins('getVisibleStatusHistory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusHistoryById($statusId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusHistoryById');
        if (!$pluginInfo) {
            return parent::getStatusHistoryById($statusId);
        } else {
            return $this->___callPlugins('getStatusHistoryById', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addStatusHistory(\Magento\Sales\Model\Order\Status\History $history)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addStatusHistory');
        if (!$pluginInfo) {
            return parent::addStatusHistory($history);
        } else {
            return $this->___callPlugins('addStatusHistory', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRealOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRealOrderId');
        if (!$pluginInfo) {
            return parent::getRealOrderId();
        } else {
            return $this->___callPlugins('getRealOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderCurrency()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOrderCurrency');
        if (!$pluginInfo) {
            return parent::getOrderCurrency();
        } else {
            return $this->___callPlugins('getOrderCurrency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatPrice($price, $addBrackets = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatPrice');
        if (!$pluginInfo) {
            return parent::formatPrice($price, $addBrackets);
        } else {
            return $this->___callPlugins('formatPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatPricePrecision($price, $precision, $addBrackets = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatPricePrecision');
        if (!$pluginInfo) {
            return parent::formatPricePrecision($price, $precision, $addBrackets);
        } else {
            return $this->___callPlugins('formatPricePrecision', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatPriceTxt($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatPriceTxt');
        if (!$pluginInfo) {
            return parent::formatPriceTxt($price);
        } else {
            return $this->___callPlugins('formatPriceTxt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseCurrency()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseCurrency');
        if (!$pluginInfo) {
            return parent::getBaseCurrency();
        } else {
            return $this->___callPlugins('getBaseCurrency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatBasePrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatBasePrice');
        if (!$pluginInfo) {
            return parent::formatBasePrice($price);
        } else {
            return $this->___callPlugins('formatBasePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatBasePricePrecision($price, $precision)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'formatBasePricePrecision');
        if (!$pluginInfo) {
            return parent::formatBasePricePrecision($price, $precision);
        } else {
            return $this->___callPlugins('formatBasePricePrecision', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCurrencyDifferent()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isCurrencyDifferent');
        if (!$pluginInfo) {
            return parent::isCurrencyDifferent();
        } else {
            return $this->___callPlugins('isCurrencyDifferent', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalDue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalDue');
        if (!$pluginInfo) {
            return parent::getTotalDue();
        } else {
            return $this->___callPlugins('getTotalDue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalDue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalDue');
        if (!$pluginInfo) {
            return parent::getBaseTotalDue();
        } else {
            return $this->___callPlugins('getBaseTotalDue', func_get_args(), $pluginInfo);
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
    public function getInvoiceCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInvoiceCollection');
        if (!$pluginInfo) {
            return parent::getInvoiceCollection();
        } else {
            return $this->___callPlugins('getInvoiceCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setInvoiceCollection(\Magento\Sales\Model\ResourceModel\Order\Invoice\Collection $invoices)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setInvoiceCollection');
        if (!$pluginInfo) {
            return parent::setInvoiceCollection($invoices);
        } else {
            return $this->___callPlugins('setInvoiceCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShipmentsCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShipmentsCollection');
        if (!$pluginInfo) {
            return parent::getShipmentsCollection();
        } else {
            return $this->___callPlugins('getShipmentsCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditmemosCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreditmemosCollection');
        if (!$pluginInfo) {
            return parent::getCreditmemosCollection();
        } else {
            return $this->___callPlugins('getCreditmemosCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTracksCollection()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTracksCollection');
        if (!$pluginInfo) {
            return parent::getTracksCollection();
        } else {
            return $this->___callPlugins('getTracksCollection', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasInvoices()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasInvoices');
        if (!$pluginInfo) {
            return parent::hasInvoices();
        } else {
            return $this->___callPlugins('hasInvoices', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasShipments()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasShipments');
        if (!$pluginInfo) {
            return parent::hasShipments();
        } else {
            return $this->___callPlugins('hasShipments', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasCreditmemos()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hasCreditmemos');
        if (!$pluginInfo) {
            return parent::hasCreditmemos();
        } else {
            return $this->___callPlugins('hasCreditmemos', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedObjects()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRelatedObjects');
        if (!$pluginInfo) {
            return parent::getRelatedObjects();
        } else {
            return $this->___callPlugins('getRelatedObjects', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerName');
        if (!$pluginInfo) {
            return parent::getCustomerName();
        } else {
            return $this->___callPlugins('getCustomerName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addRelatedObject(\Magento\Framework\Model\AbstractModel $object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addRelatedObject');
        if (!$pluginInfo) {
            return parent::addRelatedObject($object);
        } else {
            return $this->___callPlugins('addRelatedObject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAtFormatted($format)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCreatedAtFormatted');
        if (!$pluginInfo) {
            return parent::getCreatedAtFormatted($format);
        } else {
            return $this->___callPlugins('getCreatedAtFormatted', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEmailCustomerNote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEmailCustomerNote');
        if (!$pluginInfo) {
            return parent::getEmailCustomerNote();
        } else {
            return $this->___callPlugins('getEmailCustomerNote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreGroupName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreGroupName');
        if (!$pluginInfo) {
            return parent::getStoreGroupName();
        } else {
            return $this->___callPlugins('getStoreGroupName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'reset');
        if (!$pluginInfo) {
            return parent::reset();
        } else {
            return $this->___callPlugins('reset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsNotVirtual()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsNotVirtual');
        if (!$pluginInfo) {
            return parent::getIsNotVirtual();
        } else {
            return $this->___callPlugins('getIsNotVirtual', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepareInvoice($qtys = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepareInvoice');
        if (!$pluginInfo) {
            return parent::prepareInvoice($qtys);
        } else {
            return $this->___callPlugins('prepareInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isCanceled');
        if (!$pluginInfo) {
            return parent::isCanceled();
        } else {
            return $this->___callPlugins('isCanceled', func_get_args(), $pluginInfo);
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
    public function getAddresses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddresses');
        if (!$pluginInfo) {
            return parent::getAddresses();
        } else {
            return $this->___callPlugins('getAddresses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusHistories()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusHistories');
        if (!$pluginInfo) {
            return parent::getStatusHistories();
        } else {
            return $this->___callPlugins('getStatusHistories', func_get_args(), $pluginInfo);
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
    public function setExtensionAttributes(\Magento\Sales\Api\Data\OrderExtensionInterface $extensionAttributes)
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
    public function getAppliedRuleIds()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAppliedRuleIds');
        if (!$pluginInfo) {
            return parent::getAppliedRuleIds();
        } else {
            return $this->___callPlugins('getAppliedRuleIds', func_get_args(), $pluginInfo);
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
    public function getBaseDiscountCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountCanceled');
        if (!$pluginInfo) {
            return parent::getBaseDiscountCanceled();
        } else {
            return $this->___callPlugins('getBaseDiscountCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDiscountInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseDiscountInvoiced();
        } else {
            return $this->___callPlugins('getBaseDiscountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDiscountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountRefunded');
        if (!$pluginInfo) {
            return parent::getBaseDiscountRefunded();
        } else {
            return $this->___callPlugins('getBaseDiscountRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseDiscountTaxCompensationInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountTaxCompensationInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseDiscountTaxCompensationInvoiced();
        } else {
            return $this->___callPlugins('getBaseDiscountTaxCompensationInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseDiscountTaxCompensationRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseDiscountTaxCompensationRefunded');
        if (!$pluginInfo) {
            return parent::getBaseDiscountTaxCompensationRefunded();
        } else {
            return $this->___callPlugins('getBaseDiscountTaxCompensationRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseShippingCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingCanceled');
        if (!$pluginInfo) {
            return parent::getBaseShippingCanceled();
        } else {
            return $this->___callPlugins('getBaseShippingCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingDiscountAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingDiscountAmount');
        if (!$pluginInfo) {
            return parent::getBaseShippingDiscountAmount();
        } else {
            return $this->___callPlugins('getBaseShippingDiscountAmount', func_get_args(), $pluginInfo);
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
    public function getBaseShippingInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseShippingInvoiced();
        } else {
            return $this->___callPlugins('getBaseShippingInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseShippingRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingRefunded');
        if (!$pluginInfo) {
            return parent::getBaseShippingRefunded();
        } else {
            return $this->___callPlugins('getBaseShippingRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseShippingTaxRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseShippingTaxRefunded');
        if (!$pluginInfo) {
            return parent::getBaseShippingTaxRefunded();
        } else {
            return $this->___callPlugins('getBaseShippingTaxRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseSubtotalCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalCanceled');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalCanceled();
        } else {
            return $this->___callPlugins('getBaseSubtotalCanceled', func_get_args(), $pluginInfo);
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
    public function getBaseSubtotalInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalInvoiced();
        } else {
            return $this->___callPlugins('getBaseSubtotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotalRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseSubtotalRefunded');
        if (!$pluginInfo) {
            return parent::getBaseSubtotalRefunded();
        } else {
            return $this->___callPlugins('getBaseSubtotalRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseTaxCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTaxCanceled');
        if (!$pluginInfo) {
            return parent::getBaseTaxCanceled();
        } else {
            return $this->___callPlugins('getBaseTaxCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTaxInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseTaxInvoiced();
        } else {
            return $this->___callPlugins('getBaseTaxInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTaxRefunded');
        if (!$pluginInfo) {
            return parent::getBaseTaxRefunded();
        } else {
            return $this->___callPlugins('getBaseTaxRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalCanceled');
        if (!$pluginInfo) {
            return parent::getBaseTotalCanceled();
        } else {
            return $this->___callPlugins('getBaseTotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseTotalInvoiced();
        } else {
            return $this->___callPlugins('getBaseTotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalInvoicedCost()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalInvoicedCost');
        if (!$pluginInfo) {
            return parent::getBaseTotalInvoicedCost();
        } else {
            return $this->___callPlugins('getBaseTotalInvoicedCost', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalOfflineRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalOfflineRefunded');
        if (!$pluginInfo) {
            return parent::getBaseTotalOfflineRefunded();
        } else {
            return $this->___callPlugins('getBaseTotalOfflineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalOnlineRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalOnlineRefunded');
        if (!$pluginInfo) {
            return parent::getBaseTotalOnlineRefunded();
        } else {
            return $this->___callPlugins('getBaseTotalOnlineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalPaid()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalPaid');
        if (!$pluginInfo) {
            return parent::getBaseTotalPaid();
        } else {
            return $this->___callPlugins('getBaseTotalPaid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalQtyOrdered()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalQtyOrdered');
        if (!$pluginInfo) {
            return parent::getBaseTotalQtyOrdered();
        } else {
            return $this->___callPlugins('getBaseTotalQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTotalRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTotalRefunded');
        if (!$pluginInfo) {
            return parent::getBaseTotalRefunded();
        } else {
            return $this->___callPlugins('getBaseTotalRefunded', func_get_args(), $pluginInfo);
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
    public function getCanShipPartially()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCanShipPartially');
        if (!$pluginInfo) {
            return parent::getCanShipPartially();
        } else {
            return $this->___callPlugins('getCanShipPartially', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCanShipPartiallyItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCanShipPartiallyItem');
        if (!$pluginInfo) {
            return parent::getCanShipPartiallyItem();
        } else {
            return $this->___callPlugins('getCanShipPartiallyItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCouponCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCouponCode');
        if (!$pluginInfo) {
            return parent::getCouponCode();
        } else {
            return $this->___callPlugins('getCouponCode', func_get_args(), $pluginInfo);
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
    public function getCustomerDob()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerDob');
        if (!$pluginInfo) {
            return parent::getCustomerDob();
        } else {
            return $this->___callPlugins('getCustomerDob', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerEmail()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerEmail');
        if (!$pluginInfo) {
            return parent::getCustomerEmail();
        } else {
            return $this->___callPlugins('getCustomerEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerFirstname()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerFirstname');
        if (!$pluginInfo) {
            return parent::getCustomerFirstname();
        } else {
            return $this->___callPlugins('getCustomerFirstname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGender()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerGender');
        if (!$pluginInfo) {
            return parent::getCustomerGender();
        } else {
            return $this->___callPlugins('getCustomerGender', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroupId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerGroupId');
        if (!$pluginInfo) {
            return parent::getCustomerGroupId();
        } else {
            return $this->___callPlugins('getCustomerGroupId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerId');
        if (!$pluginInfo) {
            return parent::getCustomerId();
        } else {
            return $this->___callPlugins('getCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerIsGuest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerIsGuest');
        if (!$pluginInfo) {
            return parent::getCustomerIsGuest();
        } else {
            return $this->___callPlugins('getCustomerIsGuest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerLastname()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerLastname');
        if (!$pluginInfo) {
            return parent::getCustomerLastname();
        } else {
            return $this->___callPlugins('getCustomerLastname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerMiddlename()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerMiddlename');
        if (!$pluginInfo) {
            return parent::getCustomerMiddlename();
        } else {
            return $this->___callPlugins('getCustomerMiddlename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerNote()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerNote');
        if (!$pluginInfo) {
            return parent::getCustomerNote();
        } else {
            return $this->___callPlugins('getCustomerNote', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerNoteNotify()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerNoteNotify');
        if (!$pluginInfo) {
            return parent::getCustomerNoteNotify();
        } else {
            return $this->___callPlugins('getCustomerNoteNotify', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerPrefix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerPrefix');
        if (!$pluginInfo) {
            return parent::getCustomerPrefix();
        } else {
            return $this->___callPlugins('getCustomerPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerSuffix()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerSuffix');
        if (!$pluginInfo) {
            return parent::getCustomerSuffix();
        } else {
            return $this->___callPlugins('getCustomerSuffix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerTaxvat()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCustomerTaxvat');
        if (!$pluginInfo) {
            return parent::getCustomerTaxvat();
        } else {
            return $this->___callPlugins('getCustomerTaxvat', func_get_args(), $pluginInfo);
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
    public function getDiscountCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountCanceled');
        if (!$pluginInfo) {
            return parent::getDiscountCanceled();
        } else {
            return $this->___callPlugins('getDiscountCanceled', func_get_args(), $pluginInfo);
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
    public function getDiscountInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountInvoiced');
        if (!$pluginInfo) {
            return parent::getDiscountInvoiced();
        } else {
            return $this->___callPlugins('getDiscountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountRefunded');
        if (!$pluginInfo) {
            return parent::getDiscountRefunded();
        } else {
            return $this->___callPlugins('getDiscountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEditIncrement()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEditIncrement');
        if (!$pluginInfo) {
            return parent::getEditIncrement();
        } else {
            return $this->___callPlugins('getEditIncrement', func_get_args(), $pluginInfo);
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
    public function getExtCustomerId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExtCustomerId');
        if (!$pluginInfo) {
            return parent::getExtCustomerId();
        } else {
            return $this->___callPlugins('getExtCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtOrderId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExtOrderId');
        if (!$pluginInfo) {
            return parent::getExtOrderId();
        } else {
            return $this->___callPlugins('getExtOrderId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getForcedShipmentWithInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getForcedShipmentWithInvoice');
        if (!$pluginInfo) {
            return parent::getForcedShipmentWithInvoice();
        } else {
            return $this->___callPlugins('getForcedShipmentWithInvoice', func_get_args(), $pluginInfo);
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
    public function getDiscountTaxCompensationInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountTaxCompensationInvoiced');
        if (!$pluginInfo) {
            return parent::getDiscountTaxCompensationInvoiced();
        } else {
            return $this->___callPlugins('getDiscountTaxCompensationInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountTaxCompensationRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountTaxCompensationRefunded');
        if (!$pluginInfo) {
            return parent::getDiscountTaxCompensationRefunded();
        } else {
            return $this->___callPlugins('getDiscountTaxCompensationRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHoldBeforeState()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHoldBeforeState');
        if (!$pluginInfo) {
            return parent::getHoldBeforeState();
        } else {
            return $this->___callPlugins('getHoldBeforeState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHoldBeforeStatus()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHoldBeforeStatus');
        if (!$pluginInfo) {
            return parent::getHoldBeforeStatus();
        } else {
            return $this->___callPlugins('getHoldBeforeStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIsVirtual()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsVirtual');
        if (!$pluginInfo) {
            return parent::getIsVirtual();
        } else {
            return $this->___callPlugins('getIsVirtual', func_get_args(), $pluginInfo);
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
    public function getOriginalIncrementId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalIncrementId');
        if (!$pluginInfo) {
            return parent::getOriginalIncrementId();
        } else {
            return $this->___callPlugins('getOriginalIncrementId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentAuthorizationAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentAuthorizationAmount');
        if (!$pluginInfo) {
            return parent::getPaymentAuthorizationAmount();
        } else {
            return $this->___callPlugins('getPaymentAuthorizationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentAuthExpiration()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPaymentAuthExpiration');
        if (!$pluginInfo) {
            return parent::getPaymentAuthExpiration();
        } else {
            return $this->___callPlugins('getPaymentAuthExpiration', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProtectCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProtectCode');
        if (!$pluginInfo) {
            return parent::getProtectCode();
        } else {
            return $this->___callPlugins('getProtectCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteAddressId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuoteAddressId');
        if (!$pluginInfo) {
            return parent::getQuoteAddressId();
        } else {
            return $this->___callPlugins('getQuoteAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuoteId');
        if (!$pluginInfo) {
            return parent::getQuoteId();
        } else {
            return $this->___callPlugins('getQuoteId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationChildId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRelationChildId');
        if (!$pluginInfo) {
            return parent::getRelationChildId();
        } else {
            return $this->___callPlugins('getRelationChildId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationChildRealId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRelationChildRealId');
        if (!$pluginInfo) {
            return parent::getRelationChildRealId();
        } else {
            return $this->___callPlugins('getRelationChildRealId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationParentId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRelationParentId');
        if (!$pluginInfo) {
            return parent::getRelationParentId();
        } else {
            return $this->___callPlugins('getRelationParentId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationParentRealId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRelationParentRealId');
        if (!$pluginInfo) {
            return parent::getRelationParentRealId();
        } else {
            return $this->___callPlugins('getRelationParentRealId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRemoteIp()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRemoteIp');
        if (!$pluginInfo) {
            return parent::getRemoteIp();
        } else {
            return $this->___callPlugins('getRemoteIp', func_get_args(), $pluginInfo);
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
    public function getShippingCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingCanceled');
        if (!$pluginInfo) {
            return parent::getShippingCanceled();
        } else {
            return $this->___callPlugins('getShippingCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingDescription()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingDescription');
        if (!$pluginInfo) {
            return parent::getShippingDescription();
        } else {
            return $this->___callPlugins('getShippingDescription', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingDiscountAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingDiscountAmount');
        if (!$pluginInfo) {
            return parent::getShippingDiscountAmount();
        } else {
            return $this->___callPlugins('getShippingDiscountAmount', func_get_args(), $pluginInfo);
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
    public function getShippingInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingInvoiced');
        if (!$pluginInfo) {
            return parent::getShippingInvoiced();
        } else {
            return $this->___callPlugins('getShippingInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingRefunded');
        if (!$pluginInfo) {
            return parent::getShippingRefunded();
        } else {
            return $this->___callPlugins('getShippingRefunded', func_get_args(), $pluginInfo);
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
    public function getShippingTaxRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getShippingTaxRefunded');
        if (!$pluginInfo) {
            return parent::getShippingTaxRefunded();
        } else {
            return $this->___callPlugins('getShippingTaxRefunded', func_get_args(), $pluginInfo);
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
    public function getStatus()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatus');
        if (!$pluginInfo) {
            return parent::getStatus();
        } else {
            return $this->___callPlugins('getStatus', func_get_args(), $pluginInfo);
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
    public function getStoreName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStoreName');
        if (!$pluginInfo) {
            return parent::getStoreName();
        } else {
            return $this->___callPlugins('getStoreName', func_get_args(), $pluginInfo);
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
    public function getSubtotalCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalCanceled');
        if (!$pluginInfo) {
            return parent::getSubtotalCanceled();
        } else {
            return $this->___callPlugins('getSubtotalCanceled', func_get_args(), $pluginInfo);
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
    public function getSubtotalInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalInvoiced');
        if (!$pluginInfo) {
            return parent::getSubtotalInvoiced();
        } else {
            return $this->___callPlugins('getSubtotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotalRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubtotalRefunded');
        if (!$pluginInfo) {
            return parent::getSubtotalRefunded();
        } else {
            return $this->___callPlugins('getSubtotalRefunded', func_get_args(), $pluginInfo);
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
    public function getTaxCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxCanceled');
        if (!$pluginInfo) {
            return parent::getTaxCanceled();
        } else {
            return $this->___callPlugins('getTaxCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxInvoiced');
        if (!$pluginInfo) {
            return parent::getTaxInvoiced();
        } else {
            return $this->___callPlugins('getTaxInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxRefunded');
        if (!$pluginInfo) {
            return parent::getTaxRefunded();
        } else {
            return $this->___callPlugins('getTaxRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalCanceled');
        if (!$pluginInfo) {
            return parent::getTotalCanceled();
        } else {
            return $this->___callPlugins('getTotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalInvoiced');
        if (!$pluginInfo) {
            return parent::getTotalInvoiced();
        } else {
            return $this->___callPlugins('getTotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalItemCount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalItemCount');
        if (!$pluginInfo) {
            return parent::getTotalItemCount();
        } else {
            return $this->___callPlugins('getTotalItemCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalOfflineRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalOfflineRefunded');
        if (!$pluginInfo) {
            return parent::getTotalOfflineRefunded();
        } else {
            return $this->___callPlugins('getTotalOfflineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalOnlineRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalOnlineRefunded');
        if (!$pluginInfo) {
            return parent::getTotalOnlineRefunded();
        } else {
            return $this->___callPlugins('getTotalOnlineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalPaid()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalPaid');
        if (!$pluginInfo) {
            return parent::getTotalPaid();
        } else {
            return $this->___callPlugins('getTotalPaid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalQtyOrdered()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalQtyOrdered');
        if (!$pluginInfo) {
            return parent::getTotalQtyOrdered();
        } else {
            return $this->___callPlugins('getTotalQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTotalRefunded');
        if (!$pluginInfo) {
            return parent::getTotalRefunded();
        } else {
            return $this->___callPlugins('getTotalRefunded', func_get_args(), $pluginInfo);
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
    public function getWeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeight');
        if (!$pluginInfo) {
            return parent::getWeight();
        } else {
            return $this->___callPlugins('getWeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getXForwardedFor()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getXForwardedFor');
        if (!$pluginInfo) {
            return parent::getXForwardedFor();
        } else {
            return $this->___callPlugins('getXForwardedFor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStatusHistories(array $statusHistories = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStatusHistories');
        if (!$pluginInfo) {
            return parent::setStatusHistories($statusHistories);
        } else {
            return $this->___callPlugins('setStatusHistories', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStatus');
        if (!$pluginInfo) {
            return parent::setStatus($status);
        } else {
            return $this->___callPlugins('setStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCouponCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCouponCode');
        if (!$pluginInfo) {
            return parent::setCouponCode($code);
        } else {
            return $this->___callPlugins('setCouponCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setProtectCode($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setProtectCode');
        if (!$pluginInfo) {
            return parent::setProtectCode($code);
        } else {
            return $this->___callPlugins('setProtectCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingDescription($description)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingDescription');
        if (!$pluginInfo) {
            return parent::setShippingDescription($description);
        } else {
            return $this->___callPlugins('setShippingDescription', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIsVirtual($isVirtual)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIsVirtual');
        if (!$pluginInfo) {
            return parent::setIsVirtual($isVirtual);
        } else {
            return $this->___callPlugins('setIsVirtual', func_get_args(), $pluginInfo);
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
    public function setCustomerId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerId');
        if (!$pluginInfo) {
            return parent::setCustomerId($id);
        } else {
            return $this->___callPlugins('setCustomerId', func_get_args(), $pluginInfo);
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
    public function setBaseDiscountCanceled($baseDiscountCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountCanceled');
        if (!$pluginInfo) {
            return parent::setBaseDiscountCanceled($baseDiscountCanceled);
        } else {
            return $this->___callPlugins('setBaseDiscountCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountInvoiced($baseDiscountInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseDiscountInvoiced($baseDiscountInvoiced);
        } else {
            return $this->___callPlugins('setBaseDiscountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountRefunded($baseDiscountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountRefunded');
        if (!$pluginInfo) {
            return parent::setBaseDiscountRefunded($baseDiscountRefunded);
        } else {
            return $this->___callPlugins('setBaseDiscountRefunded', func_get_args(), $pluginInfo);
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
    public function setBaseShippingCanceled($baseShippingCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingCanceled');
        if (!$pluginInfo) {
            return parent::setBaseShippingCanceled($baseShippingCanceled);
        } else {
            return $this->___callPlugins('setBaseShippingCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingInvoiced($baseShippingInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseShippingInvoiced($baseShippingInvoiced);
        } else {
            return $this->___callPlugins('setBaseShippingInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseShippingRefunded($baseShippingRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingRefunded');
        if (!$pluginInfo) {
            return parent::setBaseShippingRefunded($baseShippingRefunded);
        } else {
            return $this->___callPlugins('setBaseShippingRefunded', func_get_args(), $pluginInfo);
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
    public function setBaseShippingTaxRefunded($baseShippingTaxRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingTaxRefunded');
        if (!$pluginInfo) {
            return parent::setBaseShippingTaxRefunded($baseShippingTaxRefunded);
        } else {
            return $this->___callPlugins('setBaseShippingTaxRefunded', func_get_args(), $pluginInfo);
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
    public function setBaseSubtotalCanceled($baseSubtotalCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseSubtotalCanceled');
        if (!$pluginInfo) {
            return parent::setBaseSubtotalCanceled($baseSubtotalCanceled);
        } else {
            return $this->___callPlugins('setBaseSubtotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseSubtotalInvoiced($baseSubtotalInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseSubtotalInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseSubtotalInvoiced($baseSubtotalInvoiced);
        } else {
            return $this->___callPlugins('setBaseSubtotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseSubtotalRefunded($baseSubtotalRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseSubtotalRefunded');
        if (!$pluginInfo) {
            return parent::setBaseSubtotalRefunded($baseSubtotalRefunded);
        } else {
            return $this->___callPlugins('setBaseSubtotalRefunded', func_get_args(), $pluginInfo);
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
    public function setBaseTaxCanceled($baseTaxCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTaxCanceled');
        if (!$pluginInfo) {
            return parent::setBaseTaxCanceled($baseTaxCanceled);
        } else {
            return $this->___callPlugins('setBaseTaxCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxInvoiced($baseTaxInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTaxInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseTaxInvoiced($baseTaxInvoiced);
        } else {
            return $this->___callPlugins('setBaseTaxInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxRefunded($baseTaxRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTaxRefunded');
        if (!$pluginInfo) {
            return parent::setBaseTaxRefunded($baseTaxRefunded);
        } else {
            return $this->___callPlugins('setBaseTaxRefunded', func_get_args(), $pluginInfo);
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
    public function setBaseTotalCanceled($baseTotalCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalCanceled');
        if (!$pluginInfo) {
            return parent::setBaseTotalCanceled($baseTotalCanceled);
        } else {
            return $this->___callPlugins('setBaseTotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalInvoiced($baseTotalInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseTotalInvoiced($baseTotalInvoiced);
        } else {
            return $this->___callPlugins('setBaseTotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalInvoicedCost($baseTotalInvoicedCost)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalInvoicedCost');
        if (!$pluginInfo) {
            return parent::setBaseTotalInvoicedCost($baseTotalInvoicedCost);
        } else {
            return $this->___callPlugins('setBaseTotalInvoicedCost', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalOfflineRefunded($baseTotalOfflineRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalOfflineRefunded');
        if (!$pluginInfo) {
            return parent::setBaseTotalOfflineRefunded($baseTotalOfflineRefunded);
        } else {
            return $this->___callPlugins('setBaseTotalOfflineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalOnlineRefunded($baseTotalOnlineRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalOnlineRefunded');
        if (!$pluginInfo) {
            return parent::setBaseTotalOnlineRefunded($baseTotalOnlineRefunded);
        } else {
            return $this->___callPlugins('setBaseTotalOnlineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalPaid($baseTotalPaid)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalPaid');
        if (!$pluginInfo) {
            return parent::setBaseTotalPaid($baseTotalPaid);
        } else {
            return $this->___callPlugins('setBaseTotalPaid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalQtyOrdered($baseTotalQtyOrdered)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalQtyOrdered');
        if (!$pluginInfo) {
            return parent::setBaseTotalQtyOrdered($baseTotalQtyOrdered);
        } else {
            return $this->___callPlugins('setBaseTotalQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTotalRefunded($baseTotalRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalRefunded');
        if (!$pluginInfo) {
            return parent::setBaseTotalRefunded($baseTotalRefunded);
        } else {
            return $this->___callPlugins('setBaseTotalRefunded', func_get_args(), $pluginInfo);
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
    public function setDiscountCanceled($discountCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountCanceled');
        if (!$pluginInfo) {
            return parent::setDiscountCanceled($discountCanceled);
        } else {
            return $this->___callPlugins('setDiscountCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountInvoiced($discountInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountInvoiced');
        if (!$pluginInfo) {
            return parent::setDiscountInvoiced($discountInvoiced);
        } else {
            return $this->___callPlugins('setDiscountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountRefunded($discountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountRefunded');
        if (!$pluginInfo) {
            return parent::setDiscountRefunded($discountRefunded);
        } else {
            return $this->___callPlugins('setDiscountRefunded', func_get_args(), $pluginInfo);
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
    public function setShippingCanceled($shippingCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingCanceled');
        if (!$pluginInfo) {
            return parent::setShippingCanceled($shippingCanceled);
        } else {
            return $this->___callPlugins('setShippingCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingInvoiced($shippingInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingInvoiced');
        if (!$pluginInfo) {
            return parent::setShippingInvoiced($shippingInvoiced);
        } else {
            return $this->___callPlugins('setShippingInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingRefunded($shippingRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingRefunded');
        if (!$pluginInfo) {
            return parent::setShippingRefunded($shippingRefunded);
        } else {
            return $this->___callPlugins('setShippingRefunded', func_get_args(), $pluginInfo);
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
    public function setShippingTaxRefunded($shippingTaxRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingTaxRefunded');
        if (!$pluginInfo) {
            return parent::setShippingTaxRefunded($shippingTaxRefunded);
        } else {
            return $this->___callPlugins('setShippingTaxRefunded', func_get_args(), $pluginInfo);
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
    public function setSubtotalCanceled($subtotalCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubtotalCanceled');
        if (!$pluginInfo) {
            return parent::setSubtotalCanceled($subtotalCanceled);
        } else {
            return $this->___callPlugins('setSubtotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotalInvoiced($subtotalInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubtotalInvoiced');
        if (!$pluginInfo) {
            return parent::setSubtotalInvoiced($subtotalInvoiced);
        } else {
            return $this->___callPlugins('setSubtotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotalRefunded($subtotalRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubtotalRefunded');
        if (!$pluginInfo) {
            return parent::setSubtotalRefunded($subtotalRefunded);
        } else {
            return $this->___callPlugins('setSubtotalRefunded', func_get_args(), $pluginInfo);
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
    public function setTaxCanceled($taxCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxCanceled');
        if (!$pluginInfo) {
            return parent::setTaxCanceled($taxCanceled);
        } else {
            return $this->___callPlugins('setTaxCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxInvoiced($taxInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxInvoiced');
        if (!$pluginInfo) {
            return parent::setTaxInvoiced($taxInvoiced);
        } else {
            return $this->___callPlugins('setTaxInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxRefunded($taxRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxRefunded');
        if (!$pluginInfo) {
            return parent::setTaxRefunded($taxRefunded);
        } else {
            return $this->___callPlugins('setTaxRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalCanceled($totalCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalCanceled');
        if (!$pluginInfo) {
            return parent::setTotalCanceled($totalCanceled);
        } else {
            return $this->___callPlugins('setTotalCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalInvoiced($totalInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalInvoiced');
        if (!$pluginInfo) {
            return parent::setTotalInvoiced($totalInvoiced);
        } else {
            return $this->___callPlugins('setTotalInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalOfflineRefunded($totalOfflineRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalOfflineRefunded');
        if (!$pluginInfo) {
            return parent::setTotalOfflineRefunded($totalOfflineRefunded);
        } else {
            return $this->___callPlugins('setTotalOfflineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalOnlineRefunded($totalOnlineRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalOnlineRefunded');
        if (!$pluginInfo) {
            return parent::setTotalOnlineRefunded($totalOnlineRefunded);
        } else {
            return $this->___callPlugins('setTotalOnlineRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalPaid($totalPaid)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalPaid');
        if (!$pluginInfo) {
            return parent::setTotalPaid($totalPaid);
        } else {
            return $this->___callPlugins('setTotalPaid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalQtyOrdered($totalQtyOrdered)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalQtyOrdered');
        if (!$pluginInfo) {
            return parent::setTotalQtyOrdered($totalQtyOrdered);
        } else {
            return $this->___callPlugins('setTotalQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTotalRefunded($totalRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalRefunded');
        if (!$pluginInfo) {
            return parent::setTotalRefunded($totalRefunded);
        } else {
            return $this->___callPlugins('setTotalRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCanShipPartially($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCanShipPartially');
        if (!$pluginInfo) {
            return parent::setCanShipPartially($flag);
        } else {
            return $this->___callPlugins('setCanShipPartially', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCanShipPartiallyItem($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCanShipPartiallyItem');
        if (!$pluginInfo) {
            return parent::setCanShipPartiallyItem($flag);
        } else {
            return $this->___callPlugins('setCanShipPartiallyItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerIsGuest($customerIsGuest)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerIsGuest');
        if (!$pluginInfo) {
            return parent::setCustomerIsGuest($customerIsGuest);
        } else {
            return $this->___callPlugins('setCustomerIsGuest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerNoteNotify($customerNoteNotify)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerNoteNotify');
        if (!$pluginInfo) {
            return parent::setCustomerNoteNotify($customerNoteNotify);
        } else {
            return $this->___callPlugins('setCustomerNoteNotify', func_get_args(), $pluginInfo);
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
    public function setCustomerGroupId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerGroupId');
        if (!$pluginInfo) {
            return parent::setCustomerGroupId($id);
        } else {
            return $this->___callPlugins('setCustomerGroupId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEditIncrement($editIncrement)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEditIncrement');
        if (!$pluginInfo) {
            return parent::setEditIncrement($editIncrement);
        } else {
            return $this->___callPlugins('setEditIncrement', func_get_args(), $pluginInfo);
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
    public function setForcedShipmentWithInvoice($forcedShipmentWithInvoice)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setForcedShipmentWithInvoice');
        if (!$pluginInfo) {
            return parent::setForcedShipmentWithInvoice($forcedShipmentWithInvoice);
        } else {
            return $this->___callPlugins('setForcedShipmentWithInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentAuthExpiration($paymentAuthExpiration)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPaymentAuthExpiration');
        if (!$pluginInfo) {
            return parent::setPaymentAuthExpiration($paymentAuthExpiration);
        } else {
            return $this->___callPlugins('setPaymentAuthExpiration', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteAddressId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuoteAddressId');
        if (!$pluginInfo) {
            return parent::setQuoteAddressId($id);
        } else {
            return $this->___callPlugins('setQuoteAddressId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuoteId');
        if (!$pluginInfo) {
            return parent::setQuoteId($id);
        } else {
            return $this->___callPlugins('setQuoteId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustmentNegative($adjustmentNegative)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdjustmentNegative');
        if (!$pluginInfo) {
            return parent::setAdjustmentNegative($adjustmentNegative);
        } else {
            return $this->___callPlugins('setAdjustmentNegative', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustmentPositive($adjustmentPositive)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdjustmentPositive');
        if (!$pluginInfo) {
            return parent::setAdjustmentPositive($adjustmentPositive);
        } else {
            return $this->___callPlugins('setAdjustmentPositive', func_get_args(), $pluginInfo);
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
    public function setBaseShippingDiscountAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseShippingDiscountAmount');
        if (!$pluginInfo) {
            return parent::setBaseShippingDiscountAmount($amount);
        } else {
            return $this->___callPlugins('setBaseShippingDiscountAmount', func_get_args(), $pluginInfo);
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
    public function setBaseTotalDue($baseTotalDue)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTotalDue');
        if (!$pluginInfo) {
            return parent::setBaseTotalDue($baseTotalDue);
        } else {
            return $this->___callPlugins('setBaseTotalDue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentAuthorizationAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPaymentAuthorizationAmount');
        if (!$pluginInfo) {
            return parent::setPaymentAuthorizationAmount($amount);
        } else {
            return $this->___callPlugins('setPaymentAuthorizationAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingDiscountAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingDiscountAmount');
        if (!$pluginInfo) {
            return parent::setShippingDiscountAmount($amount);
        } else {
            return $this->___callPlugins('setShippingDiscountAmount', func_get_args(), $pluginInfo);
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
    public function setTotalDue($totalDue)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalDue');
        if (!$pluginInfo) {
            return parent::setTotalDue($totalDue);
        } else {
            return $this->___callPlugins('setTotalDue', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeight($weight)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeight');
        if (!$pluginInfo) {
            return parent::setWeight($weight);
        } else {
            return $this->___callPlugins('setWeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerDob($customerDob)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerDob');
        if (!$pluginInfo) {
            return parent::setCustomerDob($customerDob);
        } else {
            return $this->___callPlugins('setCustomerDob', func_get_args(), $pluginInfo);
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
    public function setAppliedRuleIds($appliedRuleIds)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAppliedRuleIds');
        if (!$pluginInfo) {
            return parent::setAppliedRuleIds($appliedRuleIds);
        } else {
            return $this->___callPlugins('setAppliedRuleIds', func_get_args(), $pluginInfo);
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
    public function setCustomerEmail($customerEmail)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerEmail');
        if (!$pluginInfo) {
            return parent::setCustomerEmail($customerEmail);
        } else {
            return $this->___callPlugins('setCustomerEmail', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerFirstname($customerFirstname)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerFirstname');
        if (!$pluginInfo) {
            return parent::setCustomerFirstname($customerFirstname);
        } else {
            return $this->___callPlugins('setCustomerFirstname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerLastname($customerLastname)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerLastname');
        if (!$pluginInfo) {
            return parent::setCustomerLastname($customerLastname);
        } else {
            return $this->___callPlugins('setCustomerLastname', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerMiddlename($customerMiddlename)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerMiddlename');
        if (!$pluginInfo) {
            return parent::setCustomerMiddlename($customerMiddlename);
        } else {
            return $this->___callPlugins('setCustomerMiddlename', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerPrefix($customerPrefix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerPrefix');
        if (!$pluginInfo) {
            return parent::setCustomerPrefix($customerPrefix);
        } else {
            return $this->___callPlugins('setCustomerPrefix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerSuffix($customerSuffix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerSuffix');
        if (!$pluginInfo) {
            return parent::setCustomerSuffix($customerSuffix);
        } else {
            return $this->___callPlugins('setCustomerSuffix', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerTaxvat($customerTaxvat)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerTaxvat');
        if (!$pluginInfo) {
            return parent::setCustomerTaxvat($customerTaxvat);
        } else {
            return $this->___callPlugins('setCustomerTaxvat', func_get_args(), $pluginInfo);
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
    public function setExtCustomerId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setExtCustomerId');
        if (!$pluginInfo) {
            return parent::setExtCustomerId($id);
        } else {
            return $this->___callPlugins('setExtCustomerId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtOrderId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setExtOrderId');
        if (!$pluginInfo) {
            return parent::setExtOrderId($id);
        } else {
            return $this->___callPlugins('setExtOrderId', func_get_args(), $pluginInfo);
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
    public function setHoldBeforeState($holdBeforeState)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHoldBeforeState');
        if (!$pluginInfo) {
            return parent::setHoldBeforeState($holdBeforeState);
        } else {
            return $this->___callPlugins('setHoldBeforeState', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHoldBeforeStatus($holdBeforeStatus)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHoldBeforeStatus');
        if (!$pluginInfo) {
            return parent::setHoldBeforeStatus($holdBeforeStatus);
        } else {
            return $this->___callPlugins('setHoldBeforeStatus', func_get_args(), $pluginInfo);
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
    public function setOriginalIncrementId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOriginalIncrementId');
        if (!$pluginInfo) {
            return parent::setOriginalIncrementId($id);
        } else {
            return $this->___callPlugins('setOriginalIncrementId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRelationChildId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRelationChildId');
        if (!$pluginInfo) {
            return parent::setRelationChildId($id);
        } else {
            return $this->___callPlugins('setRelationChildId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRelationChildRealId($realId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRelationChildRealId');
        if (!$pluginInfo) {
            return parent::setRelationChildRealId($realId);
        } else {
            return $this->___callPlugins('setRelationChildRealId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRelationParentId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRelationParentId');
        if (!$pluginInfo) {
            return parent::setRelationParentId($id);
        } else {
            return $this->___callPlugins('setRelationParentId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRelationParentRealId($realId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRelationParentRealId');
        if (!$pluginInfo) {
            return parent::setRelationParentRealId($realId);
        } else {
            return $this->___callPlugins('setRelationParentRealId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRemoteIp($remoteIp)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRemoteIp');
        if (!$pluginInfo) {
            return parent::setRemoteIp($remoteIp);
        } else {
            return $this->___callPlugins('setRemoteIp', func_get_args(), $pluginInfo);
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
    public function setStoreName($storeName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStoreName');
        if (!$pluginInfo) {
            return parent::setStoreName($storeName);
        } else {
            return $this->___callPlugins('setStoreName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setXForwardedFor($xForwardedFor)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setXForwardedFor');
        if (!$pluginInfo) {
            return parent::setXForwardedFor($xForwardedFor);
        } else {
            return $this->___callPlugins('setXForwardedFor', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerNote($customerNote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerNote');
        if (!$pluginInfo) {
            return parent::setCustomerNote($customerNote);
        } else {
            return $this->___callPlugins('setCustomerNote', func_get_args(), $pluginInfo);
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
    public function setTotalItemCount($totalItemCount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTotalItemCount');
        if (!$pluginInfo) {
            return parent::setTotalItemCount($totalItemCount);
        } else {
            return $this->___callPlugins('setTotalItemCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGender($customerGender)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setCustomerGender');
        if (!$pluginInfo) {
            return parent::setCustomerGender($customerGender);
        } else {
            return $this->___callPlugins('setCustomerGender', func_get_args(), $pluginInfo);
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
    public function setDiscountTaxCompensationInvoiced($discountTaxCompensationInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountTaxCompensationInvoiced');
        if (!$pluginInfo) {
            return parent::setDiscountTaxCompensationInvoiced($discountTaxCompensationInvoiced);
        } else {
            return $this->___callPlugins('setDiscountTaxCompensationInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountTaxCompensationInvoiced($baseDiscountTaxCompensationInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountTaxCompensationInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseDiscountTaxCompensationInvoiced($baseDiscountTaxCompensationInvoiced);
        } else {
            return $this->___callPlugins('setBaseDiscountTaxCompensationInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountTaxCompensationRefunded($discountTaxCompensationRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountTaxCompensationRefunded');
        if (!$pluginInfo) {
            return parent::setDiscountTaxCompensationRefunded($discountTaxCompensationRefunded);
        } else {
            return $this->___callPlugins('setDiscountTaxCompensationRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseDiscountTaxCompensationRefunded($baseDiscountTaxCompensationRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseDiscountTaxCompensationRefunded');
        if (!$pluginInfo) {
            return parent::setBaseDiscountTaxCompensationRefunded($baseDiscountTaxCompensationRefunded);
        } else {
            return $this->___callPlugins('setBaseDiscountTaxCompensationRefunded', func_get_args(), $pluginInfo);
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
    public function setShippingMethod($shippingMethod)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setShippingMethod');
        if (!$pluginInfo) {
            return parent::setShippingMethod($shippingMethod);
        } else {
            return $this->___callPlugins('setShippingMethod', func_get_args(), $pluginInfo);
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
