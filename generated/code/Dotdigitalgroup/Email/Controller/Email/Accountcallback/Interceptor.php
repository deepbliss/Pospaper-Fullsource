<?php
namespace Dotdigitalgroup\Email\Controller\Email\Accountcallback;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Email\Accountcallback
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Email\Accountcallback implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Dotdigitalgroup\Email\Helper\Data $helper, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress, \Dotdigitalgroup\Email\Model\Trial\TrialSetup $trialSetup)
    {
        $this->___init();
        parent::__construct($context, $helper, $jsonHelper, $storeManager, $remoteAddress, $trialSetup);
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
