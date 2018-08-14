<?php
namespace Magento\SalesRule\Model\ResourceModel\Coupon;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\ResourceModel\Coupon
 */
class Interceptor extends \Magento\SalesRule\Model\ResourceModel\Coupon implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context, $connectionName = null)
    {
        $this->___init();
        parent::__construct($context, $connectionName);
    }

    /**
     * {@inheritdoc}
     */
    public function updateSpecificCoupons(\Magento\SalesRule\Model\Rule $rule)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateSpecificCoupons');
        if (!$pluginInfo) {
            return parent::updateSpecificCoupons($rule);
        } else {
            return $this->___callPlugins('updateSpecificCoupons', func_get_args(), $pluginInfo);
        }
    }
}
