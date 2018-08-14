<?php
namespace Magento\Authorizenet\Controller\Directpost\Payment\ReturnQuote;

/**
 * Interceptor class for @see \Magento\Authorizenet\Controller\Directpost\Payment\ReturnQuote
 */
class Interceptor extends \Magento\Authorizenet\Controller\Directpost\Payment\ReturnQuote implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Authorizenet\Helper\DataFactory $dataFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $dataFactory);
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
