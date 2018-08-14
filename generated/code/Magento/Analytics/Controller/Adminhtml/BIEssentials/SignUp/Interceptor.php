<?php
namespace Magento\Analytics\Controller\Adminhtml\BIEssentials\SignUp;

/**
 * Interceptor class for @see \Magento\Analytics\Controller\Adminhtml\BIEssentials\SignUp
 */
class Interceptor extends \Magento\Analytics\Controller\Adminhtml\BIEssentials\SignUp implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Config\ScopeConfigInterface $config)
    {
        $this->___init();
        parent::__construct($context, $config);
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
