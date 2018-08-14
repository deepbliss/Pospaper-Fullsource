<?php
namespace Magento\Catalog\Controller\Product\Compare\Remove;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Product\Compare\Remove
 */
class Interceptor extends \Magento\Catalog\Controller\Product\Compare\Remove implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\Product\Compare\ItemFactory $compareItemFactory, \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Model\Visitor $customerVisitor, \Magento\Catalog\Model\Product\Compare\ListCompare $catalogProductCompareList, \Magento\Catalog\Model\Session $catalogSession, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->___init();
        parent::__construct($context, $compareItemFactory, $itemCollectionFactory, $customerSession, $customerVisitor, $catalogProductCompareList, $catalogSession, $storeManager, $formKeyValidator, $resultPageFactory, $productRepository);
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
