<?php
namespace Magento\Catalog\Controller\Adminhtml\Product\ShowUpdateResult;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Product\ShowUpdateResult
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Product\ShowUpdateResult implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder, \Magento\Catalog\Helper\Product\Composite $productCompositeHelper)
    {
        $this->___init();
        parent::__construct($context, $productBuilder, $productCompositeHelper);
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
