<?php
namespace Magento\Sales\Model\Order\Item;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Item
 */
class Interceptor extends \Magento\Sales\Model\Order\Item implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $orderFactory, $storeManager, $productRepository, $resource, $resourceCollection, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToCancel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyToCancel');
        if (!$pluginInfo) {
            return parent::getQtyToCancel();
        } else {
            return $this->___callPlugins('getQtyToCancel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isProcessingAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isProcessingAvailable');
        if (!$pluginInfo) {
            return parent::isProcessingAvailable();
        } else {
            return $this->___callPlugins('isProcessingAvailable', func_get_args(), $pluginInfo);
        }
    }
}
