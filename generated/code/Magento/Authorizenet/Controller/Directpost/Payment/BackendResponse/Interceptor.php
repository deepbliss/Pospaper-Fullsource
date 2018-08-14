<?php
namespace Magento\Authorizenet\Controller\Directpost\Payment\BackendResponse;

/**
 * Interceptor class for @see \Magento\Authorizenet\Controller\Directpost\Payment\BackendResponse
 */
class Interceptor extends \Magento\Authorizenet\Controller\Directpost\Payment\BackendResponse implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Authorizenet\Helper\DataFactory $dataFactory, \Magento\Authorizenet\Model\DirectpostFactory $directpostFactory, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $dataFactory, $directpostFactory, $logger);
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
