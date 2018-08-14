<?php
namespace Magento\Catalog\Controller\Adminhtml\Product\Save;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Product\Save
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Product\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder, \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper, \Magento\Catalog\Model\Product\Copier $productCopier, \Magento\Catalog\Model\Product\TypeTransitionManager $productTypeManager, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->___init();
        parent::__construct($context, $productBuilder, $initializationHelper, $productCopier, $productTypeManager, $productRepository);
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
