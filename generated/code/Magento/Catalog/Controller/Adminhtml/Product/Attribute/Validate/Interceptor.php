<?php
namespace Magento\Catalog\Controller\Adminhtml\Product\Attribute\Validate;

/**
 * Interceptor class for @see \Magento\Catalog\Controller\Adminhtml\Product\Attribute\Validate
 */
class Interceptor extends \Magento\Catalog\Controller\Adminhtml\Product\Attribute\Validate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Cache\FrontendInterface $attributeLabelCache, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, array $multipleAttributeList = array())
    {
        $this->___init();
        parent::__construct($context, $attributeLabelCache, $coreRegistry, $resultPageFactory, $resultJsonFactory, $layoutFactory, $multipleAttributeList);
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
