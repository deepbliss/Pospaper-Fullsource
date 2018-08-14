<?php
namespace Magento\Quote\Model\Quote\Address\ToOrderAddress;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Address\ToOrderAddress
 */
class Interceptor extends \Magento\Quote\Model\Quote\Address\ToOrderAddress implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\Order\AddressRepository $orderAddressRepository, \Magento\Framework\DataObject\Copy $objectCopyService, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper)
    {
        $this->___init();
        parent::__construct($orderAddressRepository, $objectCopyService, $dataObjectHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function convert(\Magento\Quote\Model\Quote\Address $object, $data = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'convert');
        if (!$pluginInfo) {
            return parent::convert($object, $data);
        } else {
            return $this->___callPlugins('convert', func_get_args(), $pluginInfo);
        }
    }
}
