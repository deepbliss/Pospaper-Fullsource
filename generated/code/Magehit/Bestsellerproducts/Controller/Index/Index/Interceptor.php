<?php
namespace Magehit\Bestsellerproducts\Controller\Index\Index;

/**
 * Interceptor class for @see \Magehit\Bestsellerproducts\Controller\Index\Index
 */
class Interceptor extends \Magehit\Bestsellerproducts\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $transportBuilder, $resultPageFactory, $inlineTranslation, $scopeConfig, $storeManager, $registry);
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
