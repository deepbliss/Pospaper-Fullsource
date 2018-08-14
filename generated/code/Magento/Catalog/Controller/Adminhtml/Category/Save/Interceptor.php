<?php
namespace Magento\Catalog\Controller\Adminhtml\Category\Save;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Category\Save
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Category\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Eav\Model\Config $eavConfig = null)
    {
        $this->___init();
        parent::__construct($context, $resultRawFactory, $resultJsonFactory, $layoutFactory, $dateFilter, $storeManager, $eavConfig);
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
