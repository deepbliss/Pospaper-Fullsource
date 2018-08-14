<?php
namespace Magento\Customer\Controller\Section\Load;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Section\Load
 */
class Interceptor extends \Magento\Customer\Controller\Section\Load implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Customer\CustomerData\Section\Identifier $sectionIdentifier, \Magento\Customer\CustomerData\SectionPoolInterface $sectionPool, \Magento\Framework\Escaper $escaper = null)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $sectionIdentifier, $sectionPool, $escaper);
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
