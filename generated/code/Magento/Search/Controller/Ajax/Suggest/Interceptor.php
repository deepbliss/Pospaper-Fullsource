<?php
namespace Magento\Search\Controller\Ajax\Suggest;

/**
 * Interceptor class for @see \Magento\Search\Controller\Ajax\Suggest
 */
class Interceptor extends \Magento\Search\Controller\Ajax\Suggest implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Search\Model\AutocompleteInterface $autocomplete)
    {
        $this->___init();
        parent::__construct($context, $autocomplete);
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
