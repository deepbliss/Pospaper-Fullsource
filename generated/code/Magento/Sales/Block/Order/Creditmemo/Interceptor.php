<?php
namespace Magento\Sales\Block\Order\Creditmemo;

/**
 * Interceptor class for @see \Magento\Sales\Block\Order\Creditmemo
 */
class Interceptor extends \Magento\Sales\Block\Order\Creditmemo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\Http\Context $httpContext, \Magento\Payment\Helper\Data $paymentHelper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $httpContext, $paymentHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
