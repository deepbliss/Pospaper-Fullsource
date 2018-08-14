<?php
namespace Magento\Sales\Model\Order;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order
 */
class Interceptor extends \Magento\Sales\Model\Order implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Sales\Model\Order\Config $orderConfig, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $orderItemCollectionFactory, \Magento\Catalog\Model\Product\Visibility $productVisibility, \Magento\Sales\Api\InvoiceManagementInterface $invoiceManagement, \Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Eav\Model\Config $eavConfig, \Magento\Sales\Model\Order\Status\HistoryFactory $orderHistoryFactory, \Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory $addressCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory $paymentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Status\History\CollectionFactory $historyCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $memoCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory $trackCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollectionFactory, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productListFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $timezone, $storeManager, $orderConfig, $productRepository, $orderItemCollectionFactory, $productVisibility, $invoiceManagement, $currencyFactory, $eavConfig, $orderHistoryFactory, $addressCollectionFactory, $paymentCollectionFactory, $historyCollectionFactory, $invoiceCollectionFactory, $shipmentCollectionFactory, $memoCollectionFactory, $trackCollectionFactory, $salesOrderCollectionFactory, $priceCurrency, $productListFactory, $resource, $resourceCollection, $data);
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
    public function load($modelId, $field = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load($modelId, $field);
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
