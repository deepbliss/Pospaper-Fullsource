<?php
namespace Magento\Payment\Model\Checks\ZeroTotal;

/**
 * Interceptor class for @see \Magento\Payment\Model\Checks\ZeroTotal
 */
class Interceptor extends \Magento\Payment\Model\Checks\ZeroTotal implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct()
    {
        $this->___init();
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(\Magento\Payment\Model\MethodInterface $paymentMethod, \Magento\Quote\Model\Quote $quote)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isApplicable');
        if (!$pluginInfo) {
            return parent::isApplicable($paymentMethod, $quote);
        } else {
            return $this->___callPlugins('isApplicable', func_get_args(), $pluginInfo);
        }
    }
}
