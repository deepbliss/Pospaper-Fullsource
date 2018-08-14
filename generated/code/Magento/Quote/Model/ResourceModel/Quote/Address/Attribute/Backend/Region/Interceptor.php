<?php
namespace Magento\Quote\Model\ResourceModel\Quote\Address\Attribute\Backend\Region;

/**
 * Interceptor class for @see \Magento\Quote\Model\ResourceModel\Quote\Address\Attribute\Backend\Region
 */
class Interceptor extends \Magento\Quote\Model\ResourceModel\Quote\Address\Attribute\Backend\Region implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Directory\Model\RegionFactory $regionFactory)
    {
        $this->___init();
        parent::__construct($regionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
