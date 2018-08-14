<?php
namespace Magento\SalesRule\Model\ResourceModel\Rule\Collection;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\ResourceModel\Rule\Collection
 */
class Interceptor extends \Magento\SalesRule\Model\ResourceModel\Rule\Collection implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Data\Collection\EntityFactory $entityFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date, \Magento\Framework\DB\Adapter\AdapterInterface $connection = null, \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $date, $connection, $resource, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function setValidationFilter($websiteId, $customerGroupId, $couponCode = '', $now = null, \Magento\Quote\Model\Quote\Address $address = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setValidationFilter');
        if (!$pluginInfo) {
            return parent::setValidationFilter($websiteId, $customerGroupId, $couponCode, $now, $address);
        } else {
            return $this->___callPlugins('setValidationFilter', func_get_args(), $pluginInfo);
        }
    }
}
