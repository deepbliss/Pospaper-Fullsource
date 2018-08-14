<?php
namespace Magento\Catalog\Controller\Product\Frontend\Action\Synchronize;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Product\Frontend\Action\Synchronize
 */
class Interceptor extends \Magento\Catalog\Controller\Product\Frontend\Action\Synchronize implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\Product\ProductFrontendAction\Synchronizer $synchronizer, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        $this->___init();
        parent::__construct($context, $synchronizer, $jsonFactory);
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
