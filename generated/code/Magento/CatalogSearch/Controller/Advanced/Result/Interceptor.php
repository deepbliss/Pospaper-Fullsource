<?php
namespace Magento\CatalogSearch\Controller\Advanced\Result;

/**
 * Interceptor class for @see \Magento\CatalogSearch\Controller\Advanced\Result
 */
class Interceptor extends \Magento\CatalogSearch\Controller\Advanced\Result implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\CatalogSearch\Model\Advanced $catalogSearchAdvanced, \Magento\Framework\UrlFactory $urlFactory)
    {
        $this->___init();
        parent::__construct($context, $catalogSearchAdvanced, $urlFactory);
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
