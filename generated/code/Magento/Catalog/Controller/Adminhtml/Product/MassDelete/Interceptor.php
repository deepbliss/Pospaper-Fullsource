<?php
namespace Magento\Catalog\Controller\Adminhtml\Product\MassDelete;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Product\MassDelete
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Product\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository = null)
    {
        $this->___init();
        parent::__construct($context, $productBuilder, $filter, $collectionFactory, $productRepository);
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
