<?php
namespace Magento\CatalogWidget\Controller\Adminhtml\Product\Widget\Conditions;

/**
 * Interceptor class for @see \Magento\CatalogWidget\Controller\Adminhtml\Product\Widget\Conditions
 */
class Interceptor extends \Magento\CatalogWidget\Controller\Adminhtml\Product\Widget\Conditions implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\CatalogWidget\Model\Rule $rule)
    {
        $this->___init();
        parent::__construct($context, $rule);
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
