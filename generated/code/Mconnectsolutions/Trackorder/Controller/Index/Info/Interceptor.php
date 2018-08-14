<?php
namespace Mconnectsolutions\Trackorder\Controller\Index\Info;

/**
 * Interceptor class for @see \Mconnectsolutions\Trackorder\Controller\Index\Info
 */
class Interceptor extends \Mconnectsolutions\Trackorder\Controller\Index\Info implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Shipping\Block\Tracking\Link $link, \Magento\Shipping\Model\InfoFactory $shippingInfoFactory)
    {
        $this->___init();
        parent::__construct($context, $link, $shippingInfoFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
