<?php
namespace Magento\ConfigurableProduct\Controller\Adminhtml\Product\Wizard;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Controller\Adminhtml\Product\Wizard
 */
class Interceptor extends \Magento\ConfigurableProduct\Controller\Adminhtml\Product\Wizard implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder)
    {
        $this->___init();
        parent::__construct($context, $productBuilder);
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
