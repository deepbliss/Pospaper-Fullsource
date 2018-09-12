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
    public function setParentItem($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setParentItem');
        if (!$pluginInfo) {
            return parent::setParentItem($item);
        } else {
            return $this->___callPlugins('setParentItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParentItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getParentItem');
        if (!$pluginInfo) {
            return parent::getParentItem();
        } else {
            return $this->___callPlugins('getParentItem', func_get_args(), $pluginInfo);
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
    public function getQtyToShip()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyToShip');
        if (!$pluginInfo) {
            return parent::getQtyToShip();
        } else {
            return $this->___callPlugins('getQtyToShip', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSimpleQtyToShip()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSimpleQtyToShip');
        if (!$pluginInfo) {
            return parent::getSimpleQtyToShip();
        } else {
            return $this->___callPlugins('getSimpleQtyToShip', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyToInvoice');
        if (!$pluginInfo) {
            return parent::getQtyToInvoice();
        } else {
            return $this->___callPlugins('getQtyToInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyToRefund()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyToRefund');
        if (!$pluginInfo) {
            return parent::getQtyToRefund();
        } else {
            return $this->___callPlugins('getQtyToRefund', func_get_args(), $pluginInfo);
        }
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
    public function getStatusId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusId');
        if (!$pluginInfo) {
            return parent::getStatusId();
        } else {
            return $this->___callPlugins('getStatusId', func_get_args(), $pluginInfo);
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
    public function getOriginalPrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalPrice');
        if (!$pluginInfo) {
            return parent::getOriginalPrice();
        } else {
            return $this->___callPlugins('getOriginalPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setProductOptions(array $options = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setProductOptions');
        if (!$pluginInfo) {
            return parent::setProductOptions($options);
        } else {
            return $this->___callPlugins('setProductOptions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptions()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductOptions');
        if (!$pluginInfo) {
            return parent::getProductOptions();
        } else {
            return $this->___callPlugins('getProductOptions', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptionByCode($code = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductOptionByCode');
        if (!$pluginInfo) {
            return parent::getProductOptionByCode($code);
        } else {
            return $this->___callPlugins('getProductOptionByCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRealProductType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRealProductType');
        if (!$pluginInfo) {
            return parent::getRealProductType();
        } else {
            return $this->___callPlugins('getRealProductType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addChildItem($item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addChildItem');
        if (!$pluginInfo) {
            return parent::addChildItem($item);
        } else {
            return $this->___callPlugins('addChildItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChildrenItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getChildrenItems');
        if (!$pluginInfo) {
            return parent::getChildrenItems();
        } else {
            return $this->___callPlugins('getChildrenItems', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isChildrenCalculated()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isChildrenCalculated');
        if (!$pluginInfo) {
            return parent::isChildrenCalculated();
        } else {
            return $this->___callPlugins('isChildrenCalculated', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getForceApplyDiscountToParentItem()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getForceApplyDiscountToParentItem');
        if (!$pluginInfo) {
            return parent::getForceApplyDiscountToParentItem();
        } else {
            return $this->___callPlugins('getForceApplyDiscountToParentItem', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isShipSeparately()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isShipSeparately');
        if (!$pluginInfo) {
            return parent::isShipSeparately();
        } else {
            return $this->___callPlugins('isShipSeparately', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isDummy($shipment = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isDummy');
        if (!$pluginInfo) {
            return parent::isDummy($shipment);
        } else {
            return $this->___callPlugins('isDummy', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBuyRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBuyRequest');
        if (!$pluginInfo) {
            return parent::getBuyRequest();
        } else {
            return $this->___callPlugins('getBuyRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProduct');
        if (!$pluginInfo) {
            return parent::getProduct();
        } else {
            return $this->___callPlugins('getProduct', func_get_args(), $pluginInfo);
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
    public function getAdditionalData()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAdditionalData');
        if (!$pluginInfo) {
            return parent::getAdditionalData();
        } else {
            return $this->___callPlugins('getAdditionalData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAmountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAmountRefunded');
        if (!$pluginInfo) {
            return parent::getAmountRefunded();
        } else {
            return $this->___callPlugins('getAmountRefunded', func_get_args(), $pluginInfo);
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
    public function getBaseAmountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseAmountRefunded');
        if (!$pluginInfo) {
            return parent::getBaseAmountRefunded();
        } else {
            return $this->___callPlugins('getBaseAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseCost()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseCost');
        if (!$pluginInfo) {
            return parent::getBaseCost();
        } else {
            return $this->___callPlugins('getBaseCost', func_get_args(), $pluginInfo);
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
    public function getBaseOriginalPrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseOriginalPrice');
        if (!$pluginInfo) {
            return parent::getBaseOriginalPrice();
        } else {
            return $this->___callPlugins('getBaseOriginalPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBasePrice');
        if (!$pluginInfo) {
            return parent::getBasePrice();
        } else {
            return $this->___callPlugins('getBasePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePriceInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBasePriceInclTax');
        if (!$pluginInfo) {
            return parent::getBasePriceInclTax();
        } else {
            return $this->___callPlugins('getBasePriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseRowInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseRowInvoiced');
        if (!$pluginInfo) {
            return parent::getBaseRowInvoiced();
        } else {
            return $this->___callPlugins('getBaseRowInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseRowTotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseRowTotal');
        if (!$pluginInfo) {
            return parent::getBaseRowTotal();
        } else {
            return $this->___callPlugins('getBaseRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseRowTotalInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseRowTotalInclTax');
        if (!$pluginInfo) {
            return parent::getBaseRowTotalInclTax();
        } else {
            return $this->___callPlugins('getBaseRowTotalInclTax', func_get_args(), $pluginInfo);
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
    public function getBaseTaxBeforeDiscount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseTaxBeforeDiscount');
        if (!$pluginInfo) {
            return parent::getBaseTaxBeforeDiscount();
        } else {
            return $this->___callPlugins('getBaseTaxBeforeDiscount', func_get_args(), $pluginInfo);
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
    public function getBaseWeeeTaxAppliedAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseWeeeTaxAppliedAmount');
        if (!$pluginInfo) {
            return parent::getBaseWeeeTaxAppliedAmount();
        } else {
            return $this->___callPlugins('getBaseWeeeTaxAppliedAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseWeeeTaxAppliedRowAmnt()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseWeeeTaxAppliedRowAmnt');
        if (!$pluginInfo) {
            return parent::getBaseWeeeTaxAppliedRowAmnt();
        } else {
            return $this->___callPlugins('getBaseWeeeTaxAppliedRowAmnt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseWeeeTaxDisposition()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseWeeeTaxDisposition');
        if (!$pluginInfo) {
            return parent::getBaseWeeeTaxDisposition();
        } else {
            return $this->___callPlugins('getBaseWeeeTaxDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseWeeeTaxRowDisposition()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBaseWeeeTaxRowDisposition');
        if (!$pluginInfo) {
            return parent::getBaseWeeeTaxRowDisposition();
        } else {
            return $this->___callPlugins('getBaseWeeeTaxRowDisposition', func_get_args(), $pluginInfo);
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
    public function getDescription()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDescription');
        if (!$pluginInfo) {
            return parent::getDescription();
        } else {
            return $this->___callPlugins('getDescription', func_get_args(), $pluginInfo);
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
    public function getDiscountPercent()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountPercent');
        if (!$pluginInfo) {
            return parent::getDiscountPercent();
        } else {
            return $this->___callPlugins('getDiscountPercent', func_get_args(), $pluginInfo);
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
    public function getEventId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEventId');
        if (!$pluginInfo) {
            return parent::getEventId();
        } else {
            return $this->___callPlugins('getEventId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtOrderItemId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getExtOrderItemId');
        if (!$pluginInfo) {
            return parent::getExtOrderItemId();
        } else {
            return $this->___callPlugins('getExtOrderItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFreeShipping()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFreeShipping');
        if (!$pluginInfo) {
            return parent::getFreeShipping();
        } else {
            return $this->___callPlugins('getFreeShipping', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBasePrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBasePrice');
        if (!$pluginInfo) {
            return parent::getGwBasePrice();
        } else {
            return $this->___callPlugins('getGwBasePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBasePriceInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBasePriceInvoiced');
        if (!$pluginInfo) {
            return parent::getGwBasePriceInvoiced();
        } else {
            return $this->___callPlugins('getGwBasePriceInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBasePriceRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBasePriceRefunded');
        if (!$pluginInfo) {
            return parent::getGwBasePriceRefunded();
        } else {
            return $this->___callPlugins('getGwBasePriceRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBaseTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBaseTaxAmount');
        if (!$pluginInfo) {
            return parent::getGwBaseTaxAmount();
        } else {
            return $this->___callPlugins('getGwBaseTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBaseTaxAmountInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBaseTaxAmountInvoiced');
        if (!$pluginInfo) {
            return parent::getGwBaseTaxAmountInvoiced();
        } else {
            return $this->___callPlugins('getGwBaseTaxAmountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwBaseTaxAmountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwBaseTaxAmountRefunded');
        if (!$pluginInfo) {
            return parent::getGwBaseTaxAmountRefunded();
        } else {
            return $this->___callPlugins('getGwBaseTaxAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwId');
        if (!$pluginInfo) {
            return parent::getGwId();
        } else {
            return $this->___callPlugins('getGwId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwPrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwPrice');
        if (!$pluginInfo) {
            return parent::getGwPrice();
        } else {
            return $this->___callPlugins('getGwPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwPriceInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwPriceInvoiced');
        if (!$pluginInfo) {
            return parent::getGwPriceInvoiced();
        } else {
            return $this->___callPlugins('getGwPriceInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwPriceRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwPriceRefunded');
        if (!$pluginInfo) {
            return parent::getGwPriceRefunded();
        } else {
            return $this->___callPlugins('getGwPriceRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwTaxAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwTaxAmount');
        if (!$pluginInfo) {
            return parent::getGwTaxAmount();
        } else {
            return $this->___callPlugins('getGwTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwTaxAmountInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwTaxAmountInvoiced');
        if (!$pluginInfo) {
            return parent::getGwTaxAmountInvoiced();
        } else {
            return $this->___callPlugins('getGwTaxAmountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getGwTaxAmountRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGwTaxAmountRefunded');
        if (!$pluginInfo) {
            return parent::getGwTaxAmountRefunded();
        } else {
            return $this->___callPlugins('getGwTaxAmountRefunded', func_get_args(), $pluginInfo);
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
    public function getDiscountTaxCompensationCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDiscountTaxCompensationCanceled');
        if (!$pluginInfo) {
            return parent::getDiscountTaxCompensationCanceled();
        } else {
            return $this->___callPlugins('getDiscountTaxCompensationCanceled', func_get_args(), $pluginInfo);
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
    public function getIsQtyDecimal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getIsQtyDecimal');
        if (!$pluginInfo) {
            return parent::getIsQtyDecimal();
        } else {
            return $this->___callPlugins('getIsQtyDecimal', func_get_args(), $pluginInfo);
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
    public function getItemId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemId');
        if (!$pluginInfo) {
            return parent::getItemId();
        } else {
            return $this->___callPlugins('getItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLockedDoInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLockedDoInvoice');
        if (!$pluginInfo) {
            return parent::getLockedDoInvoice();
        } else {
            return $this->___callPlugins('getLockedDoInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLockedDoShip()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLockedDoShip');
        if (!$pluginInfo) {
            return parent::getLockedDoShip();
        } else {
            return $this->___callPlugins('getLockedDoShip', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getName');
        if (!$pluginInfo) {
            return parent::getName();
        } else {
            return $this->___callPlugins('getName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getNoDiscount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getNoDiscount');
        if (!$pluginInfo) {
            return parent::getNoDiscount();
        } else {
            return $this->___callPlugins('getNoDiscount', func_get_args(), $pluginInfo);
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
    public function getParentItemId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getParentItemId');
        if (!$pluginInfo) {
            return parent::getParentItemId();
        } else {
            return $this->___callPlugins('getParentItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPrice');
        if (!$pluginInfo) {
            return parent::getPrice();
        } else {
            return $this->___callPlugins('getPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriceInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPriceInclTax');
        if (!$pluginInfo) {
            return parent::getPriceInclTax();
        } else {
            return $this->___callPlugins('getPriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductId');
        if (!$pluginInfo) {
            return parent::getProductId();
        } else {
            return $this->___callPlugins('getProductId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductType');
        if (!$pluginInfo) {
            return parent::getProductType();
        } else {
            return $this->___callPlugins('getProductType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyBackordered()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyBackordered');
        if (!$pluginInfo) {
            return parent::getQtyBackordered();
        } else {
            return $this->___callPlugins('getQtyBackordered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyCanceled()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyCanceled');
        if (!$pluginInfo) {
            return parent::getQtyCanceled();
        } else {
            return $this->___callPlugins('getQtyCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyInvoiced');
        if (!$pluginInfo) {
            return parent::getQtyInvoiced();
        } else {
            return $this->___callPlugins('getQtyInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyOrdered()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyOrdered');
        if (!$pluginInfo) {
            return parent::getQtyOrdered();
        } else {
            return $this->___callPlugins('getQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyRefunded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyRefunded');
        if (!$pluginInfo) {
            return parent::getQtyRefunded();
        } else {
            return $this->___callPlugins('getQtyRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyReturned()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyReturned');
        if (!$pluginInfo) {
            return parent::getQtyReturned();
        } else {
            return $this->___callPlugins('getQtyReturned', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQtyShipped()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQtyShipped');
        if (!$pluginInfo) {
            return parent::getQtyShipped();
        } else {
            return $this->___callPlugins('getQtyShipped', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQuoteItemId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getQuoteItemId');
        if (!$pluginInfo) {
            return parent::getQuoteItemId();
        } else {
            return $this->___callPlugins('getQuoteItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRowInvoiced()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRowInvoiced');
        if (!$pluginInfo) {
            return parent::getRowInvoiced();
        } else {
            return $this->___callPlugins('getRowInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRowTotal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRowTotal');
        if (!$pluginInfo) {
            return parent::getRowTotal();
        } else {
            return $this->___callPlugins('getRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRowTotalInclTax()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRowTotalInclTax');
        if (!$pluginInfo) {
            return parent::getRowTotalInclTax();
        } else {
            return $this->___callPlugins('getRowTotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRowWeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRowWeight');
        if (!$pluginInfo) {
            return parent::getRowWeight();
        } else {
            return $this->___callPlugins('getRowWeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSku');
        if (!$pluginInfo) {
            return parent::getSku();
        } else {
            return $this->___callPlugins('getSku', func_get_args(), $pluginInfo);
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
    public function getTaxBeforeDiscount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxBeforeDiscount');
        if (!$pluginInfo) {
            return parent::getTaxBeforeDiscount();
        } else {
            return $this->___callPlugins('getTaxBeforeDiscount', func_get_args(), $pluginInfo);
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
    public function getTaxPercent()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTaxPercent');
        if (!$pluginInfo) {
            return parent::getTaxPercent();
        } else {
            return $this->___callPlugins('getTaxPercent', func_get_args(), $pluginInfo);
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
    public function getWeeeTaxApplied()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeeeTaxApplied');
        if (!$pluginInfo) {
            return parent::getWeeeTaxApplied();
        } else {
            return $this->___callPlugins('getWeeeTaxApplied', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWeeeTaxAppliedAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeeeTaxAppliedAmount');
        if (!$pluginInfo) {
            return parent::getWeeeTaxAppliedAmount();
        } else {
            return $this->___callPlugins('getWeeeTaxAppliedAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWeeeTaxAppliedRowAmount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeeeTaxAppliedRowAmount');
        if (!$pluginInfo) {
            return parent::getWeeeTaxAppliedRowAmount();
        } else {
            return $this->___callPlugins('getWeeeTaxAppliedRowAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWeeeTaxDisposition()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeeeTaxDisposition');
        if (!$pluginInfo) {
            return parent::getWeeeTaxDisposition();
        } else {
            return $this->___callPlugins('getWeeeTaxDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWeeeTaxRowDisposition()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWeeeTaxRowDisposition');
        if (!$pluginInfo) {
            return parent::getWeeeTaxRowDisposition();
        } else {
            return $this->___callPlugins('getWeeeTaxRowDisposition', func_get_args(), $pluginInfo);
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
    public function setItemId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setItemId');
        if (!$pluginInfo) {
            return parent::setItemId($id);
        } else {
            return $this->___callPlugins('setItemId', func_get_args(), $pluginInfo);
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
    public function setParentItemId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setParentItemId');
        if (!$pluginInfo) {
            return parent::setParentItemId($id);
        } else {
            return $this->___callPlugins('setParentItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQuoteItemId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuoteItemId');
        if (!$pluginInfo) {
            return parent::setQuoteItemId($id);
        } else {
            return $this->___callPlugins('setQuoteItemId', func_get_args(), $pluginInfo);
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
    public function setProductId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setProductId');
        if (!$pluginInfo) {
            return parent::setProductId($id);
        } else {
            return $this->___callPlugins('setProductId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setProductType($productType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setProductType');
        if (!$pluginInfo) {
            return parent::setProductType($productType);
        } else {
            return $this->___callPlugins('setProductType', func_get_args(), $pluginInfo);
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
    public function setSku($sku)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSku');
        if (!$pluginInfo) {
            return parent::setSku($sku);
        } else {
            return $this->___callPlugins('setSku', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setName');
        if (!$pluginInfo) {
            return parent::setName($name);
        } else {
            return $this->___callPlugins('setName', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDescription');
        if (!$pluginInfo) {
            return parent::setDescription($description);
        } else {
            return $this->___callPlugins('setDescription', func_get_args(), $pluginInfo);
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
    public function setAdditionalData($additionalData)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdditionalData');
        if (!$pluginInfo) {
            return parent::setAdditionalData($additionalData);
        } else {
            return $this->___callPlugins('setAdditionalData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setIsQtyDecimal($isQtyDecimal)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setIsQtyDecimal');
        if (!$pluginInfo) {
            return parent::setIsQtyDecimal($isQtyDecimal);
        } else {
            return $this->___callPlugins('setIsQtyDecimal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setNoDiscount($noDiscount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setNoDiscount');
        if (!$pluginInfo) {
            return parent::setNoDiscount($noDiscount);
        } else {
            return $this->___callPlugins('setNoDiscount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyBackordered($qtyBackordered)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyBackordered');
        if (!$pluginInfo) {
            return parent::setQtyBackordered($qtyBackordered);
        } else {
            return $this->___callPlugins('setQtyBackordered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyCanceled($qtyCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyCanceled');
        if (!$pluginInfo) {
            return parent::setQtyCanceled($qtyCanceled);
        } else {
            return $this->___callPlugins('setQtyCanceled', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyInvoiced($qtyInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyInvoiced');
        if (!$pluginInfo) {
            return parent::setQtyInvoiced($qtyInvoiced);
        } else {
            return $this->___callPlugins('setQtyInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyOrdered($qtyOrdered)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyOrdered');
        if (!$pluginInfo) {
            return parent::setQtyOrdered($qtyOrdered);
        } else {
            return $this->___callPlugins('setQtyOrdered', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyRefunded($qtyRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyRefunded');
        if (!$pluginInfo) {
            return parent::setQtyRefunded($qtyRefunded);
        } else {
            return $this->___callPlugins('setQtyRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyShipped($qtyShipped)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyShipped');
        if (!$pluginInfo) {
            return parent::setQtyShipped($qtyShipped);
        } else {
            return $this->___callPlugins('setQtyShipped', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseCost($baseCost)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseCost');
        if (!$pluginInfo) {
            return parent::setBaseCost($baseCost);
        } else {
            return $this->___callPlugins('setBaseCost', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPrice');
        if (!$pluginInfo) {
            return parent::setPrice($price);
        } else {
            return $this->___callPlugins('setPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBasePrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBasePrice');
        if (!$pluginInfo) {
            return parent::setBasePrice($price);
        } else {
            return $this->___callPlugins('setBasePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOriginalPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setOriginalPrice');
        if (!$pluginInfo) {
            return parent::setOriginalPrice($price);
        } else {
            return $this->___callPlugins('setOriginalPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseOriginalPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseOriginalPrice');
        if (!$pluginInfo) {
            return parent::setBaseOriginalPrice($price);
        } else {
            return $this->___callPlugins('setBaseOriginalPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxPercent($taxPercent)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxPercent');
        if (!$pluginInfo) {
            return parent::setTaxPercent($taxPercent);
        } else {
            return $this->___callPlugins('setTaxPercent', func_get_args(), $pluginInfo);
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
    public function setDiscountPercent($discountPercent)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountPercent');
        if (!$pluginInfo) {
            return parent::setDiscountPercent($discountPercent);
        } else {
            return $this->___callPlugins('setDiscountPercent', func_get_args(), $pluginInfo);
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
    public function setAmountRefunded($amountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAmountRefunded');
        if (!$pluginInfo) {
            return parent::setAmountRefunded($amountRefunded);
        } else {
            return $this->___callPlugins('setAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseAmountRefunded($baseAmountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseAmountRefunded');
        if (!$pluginInfo) {
            return parent::setBaseAmountRefunded($baseAmountRefunded);
        } else {
            return $this->___callPlugins('setBaseAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRowTotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRowTotal');
        if (!$pluginInfo) {
            return parent::setRowTotal($amount);
        } else {
            return $this->___callPlugins('setRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseRowTotal($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseRowTotal');
        if (!$pluginInfo) {
            return parent::setBaseRowTotal($amount);
        } else {
            return $this->___callPlugins('setBaseRowTotal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRowInvoiced($rowInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRowInvoiced');
        if (!$pluginInfo) {
            return parent::setRowInvoiced($rowInvoiced);
        } else {
            return $this->___callPlugins('setRowInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseRowInvoiced($baseRowInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseRowInvoiced');
        if (!$pluginInfo) {
            return parent::setBaseRowInvoiced($baseRowInvoiced);
        } else {
            return $this->___callPlugins('setBaseRowInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRowWeight($rowWeight)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRowWeight');
        if (!$pluginInfo) {
            return parent::setRowWeight($rowWeight);
        } else {
            return $this->___callPlugins('setRowWeight', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxBeforeDiscount($baseTaxBeforeDiscount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseTaxBeforeDiscount');
        if (!$pluginInfo) {
            return parent::setBaseTaxBeforeDiscount($baseTaxBeforeDiscount);
        } else {
            return $this->___callPlugins('setBaseTaxBeforeDiscount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxBeforeDiscount($taxBeforeDiscount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setTaxBeforeDiscount');
        if (!$pluginInfo) {
            return parent::setTaxBeforeDiscount($taxBeforeDiscount);
        } else {
            return $this->___callPlugins('setTaxBeforeDiscount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setExtOrderItemId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setExtOrderItemId');
        if (!$pluginInfo) {
            return parent::setExtOrderItemId($id);
        } else {
            return $this->___callPlugins('setExtOrderItemId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLockedDoInvoice($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setLockedDoInvoice');
        if (!$pluginInfo) {
            return parent::setLockedDoInvoice($flag);
        } else {
            return $this->___callPlugins('setLockedDoInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setLockedDoShip($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setLockedDoShip');
        if (!$pluginInfo) {
            return parent::setLockedDoShip($flag);
        } else {
            return $this->___callPlugins('setLockedDoShip', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setPriceInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPriceInclTax');
        if (!$pluginInfo) {
            return parent::setPriceInclTax($amount);
        } else {
            return $this->___callPlugins('setPriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBasePriceInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBasePriceInclTax');
        if (!$pluginInfo) {
            return parent::setBasePriceInclTax($amount);
        } else {
            return $this->___callPlugins('setBasePriceInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setRowTotalInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setRowTotalInclTax');
        if (!$pluginInfo) {
            return parent::setRowTotalInclTax($amount);
        } else {
            return $this->___callPlugins('setRowTotalInclTax', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseRowTotalInclTax($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseRowTotalInclTax');
        if (!$pluginInfo) {
            return parent::setBaseRowTotalInclTax($amount);
        } else {
            return $this->___callPlugins('setBaseRowTotalInclTax', func_get_args(), $pluginInfo);
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
    public function setDiscountTaxCompensationCanceled($discountTaxCompensationCanceled)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDiscountTaxCompensationCanceled');
        if (!$pluginInfo) {
            return parent::setDiscountTaxCompensationCanceled($discountTaxCompensationCanceled);
        } else {
            return $this->___callPlugins('setDiscountTaxCompensationCanceled', func_get_args(), $pluginInfo);
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
    public function setGwId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwId');
        if (!$pluginInfo) {
            return parent::setGwId($id);
        } else {
            return $this->___callPlugins('setGwId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBasePrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBasePrice');
        if (!$pluginInfo) {
            return parent::setGwBasePrice($price);
        } else {
            return $this->___callPlugins('setGwBasePrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwPrice($price)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwPrice');
        if (!$pluginInfo) {
            return parent::setGwPrice($price);
        } else {
            return $this->___callPlugins('setGwPrice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBaseTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBaseTaxAmount');
        if (!$pluginInfo) {
            return parent::setGwBaseTaxAmount($amount);
        } else {
            return $this->___callPlugins('setGwBaseTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwTaxAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwTaxAmount');
        if (!$pluginInfo) {
            return parent::setGwTaxAmount($amount);
        } else {
            return $this->___callPlugins('setGwTaxAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBasePriceInvoiced($gwBasePriceInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBasePriceInvoiced');
        if (!$pluginInfo) {
            return parent::setGwBasePriceInvoiced($gwBasePriceInvoiced);
        } else {
            return $this->___callPlugins('setGwBasePriceInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwPriceInvoiced($gwPriceInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwPriceInvoiced');
        if (!$pluginInfo) {
            return parent::setGwPriceInvoiced($gwPriceInvoiced);
        } else {
            return $this->___callPlugins('setGwPriceInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBaseTaxAmountInvoiced($gwBaseTaxAmountInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBaseTaxAmountInvoiced');
        if (!$pluginInfo) {
            return parent::setGwBaseTaxAmountInvoiced($gwBaseTaxAmountInvoiced);
        } else {
            return $this->___callPlugins('setGwBaseTaxAmountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwTaxAmountInvoiced($gwTaxAmountInvoiced)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwTaxAmountInvoiced');
        if (!$pluginInfo) {
            return parent::setGwTaxAmountInvoiced($gwTaxAmountInvoiced);
        } else {
            return $this->___callPlugins('setGwTaxAmountInvoiced', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBasePriceRefunded($gwBasePriceRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBasePriceRefunded');
        if (!$pluginInfo) {
            return parent::setGwBasePriceRefunded($gwBasePriceRefunded);
        } else {
            return $this->___callPlugins('setGwBasePriceRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwPriceRefunded($gwPriceRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwPriceRefunded');
        if (!$pluginInfo) {
            return parent::setGwPriceRefunded($gwPriceRefunded);
        } else {
            return $this->___callPlugins('setGwPriceRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwBaseTaxAmountRefunded($gwBaseTaxAmountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwBaseTaxAmountRefunded');
        if (!$pluginInfo) {
            return parent::setGwBaseTaxAmountRefunded($gwBaseTaxAmountRefunded);
        } else {
            return $this->___callPlugins('setGwBaseTaxAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setGwTaxAmountRefunded($gwTaxAmountRefunded)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setGwTaxAmountRefunded');
        if (!$pluginInfo) {
            return parent::setGwTaxAmountRefunded($gwTaxAmountRefunded);
        } else {
            return $this->___callPlugins('setGwTaxAmountRefunded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFreeShipping($freeShipping)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFreeShipping');
        if (!$pluginInfo) {
            return parent::setFreeShipping($freeShipping);
        } else {
            return $this->___callPlugins('setFreeShipping', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQtyReturned($qtyReturned)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQtyReturned');
        if (!$pluginInfo) {
            return parent::setQtyReturned($qtyReturned);
        } else {
            return $this->___callPlugins('setQtyReturned', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEventId($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEventId');
        if (!$pluginInfo) {
            return parent::setEventId($id);
        } else {
            return $this->___callPlugins('setEventId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseWeeeTaxAppliedAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseWeeeTaxAppliedAmount');
        if (!$pluginInfo) {
            return parent::setBaseWeeeTaxAppliedAmount($amount);
        } else {
            return $this->___callPlugins('setBaseWeeeTaxAppliedAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseWeeeTaxAppliedRowAmnt($amnt)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseWeeeTaxAppliedRowAmnt');
        if (!$pluginInfo) {
            return parent::setBaseWeeeTaxAppliedRowAmnt($amnt);
        } else {
            return $this->___callPlugins('setBaseWeeeTaxAppliedRowAmnt', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeeeTaxAppliedAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeeeTaxAppliedAmount');
        if (!$pluginInfo) {
            return parent::setWeeeTaxAppliedAmount($amount);
        } else {
            return $this->___callPlugins('setWeeeTaxAppliedAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeeeTaxAppliedRowAmount($amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeeeTaxAppliedRowAmount');
        if (!$pluginInfo) {
            return parent::setWeeeTaxAppliedRowAmount($amount);
        } else {
            return $this->___callPlugins('setWeeeTaxAppliedRowAmount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeeeTaxApplied($weeeTaxApplied)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeeeTaxApplied');
        if (!$pluginInfo) {
            return parent::setWeeeTaxApplied($weeeTaxApplied);
        } else {
            return $this->___callPlugins('setWeeeTaxApplied', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeeeTaxDisposition($weeeTaxDisposition)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeeeTaxDisposition');
        if (!$pluginInfo) {
            return parent::setWeeeTaxDisposition($weeeTaxDisposition);
        } else {
            return $this->___callPlugins('setWeeeTaxDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setWeeeTaxRowDisposition($weeeTaxRowDisposition)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWeeeTaxRowDisposition');
        if (!$pluginInfo) {
            return parent::setWeeeTaxRowDisposition($weeeTaxRowDisposition);
        } else {
            return $this->___callPlugins('setWeeeTaxRowDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseWeeeTaxDisposition($baseWeeeTaxDisposition)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseWeeeTaxDisposition');
        if (!$pluginInfo) {
            return parent::setBaseWeeeTaxDisposition($baseWeeeTaxDisposition);
        } else {
            return $this->___callPlugins('setBaseWeeeTaxDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseWeeeTaxRowDisposition($baseWeeeTaxRowDisposition)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBaseWeeeTaxRowDisposition');
        if (!$pluginInfo) {
            return parent::setBaseWeeeTaxRowDisposition($baseWeeeTaxRowDisposition);
        } else {
            return $this->___callPlugins('setBaseWeeeTaxRowDisposition', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOption()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getProductOption');
        if (!$pluginInfo) {
            return parent::getProductOption();
        } else {
            return $this->___callPlugins('getProductOption', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setProductOption(\Magento\Catalog\Api\Data\ProductOptionInterface $productOption)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setProductOption');
        if (!$pluginInfo) {
            return parent::setProductOption($productOption);
        } else {
            return $this->___callPlugins('setProductOption', func_get_args(), $pluginInfo);
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
    public function setExtensionAttributes(\Magento\Sales\Api\Data\OrderItemExtensionInterface $extensionAttributes)
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
    public function isProcessingAvailable()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isProcessingAvailable');
        if (!$pluginInfo) {
            return parent::isProcessingAvailable();
        } else {
            return $this->___callPlugins('isProcessingAvailable', func_get_args(), $pluginInfo);
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
