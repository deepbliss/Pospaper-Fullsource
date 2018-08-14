<?php
namespace Magento\Checkout\Controller\Index\Index;

/**
 * Interceptor class for @see \Magento\Checkout\Controller\Index\Index
 */
class Interceptor extends \Magento\Checkout\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Translate\InlineInterface $translateInline, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $customerRepository, $accountManagement, $coreRegistry, $translateInline, $formKeyValidator, $scopeConfig, $layoutFactory, $quoteRepository, $resultPageFactory, $resultLayoutFactory, $resultRawFactory, $resultJsonFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
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
