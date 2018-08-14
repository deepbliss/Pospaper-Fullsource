<?php
namespace Magento\Rss\Controller\Adminhtml\Feed\Index;

/**
 * Interceptor class for @see \Magento\Rss\Controller\Adminhtml\Feed\Index
 */
class Interceptor extends \Magento\Rss\Controller\Adminhtml\Feed\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Rss\Model\RssManager $rssManager, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Rss\Model\RssFactory $rssFactory)
    {
        $this->___init();
        parent::__construct($context, $rssManager, $scopeConfig, $rssFactory);
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
