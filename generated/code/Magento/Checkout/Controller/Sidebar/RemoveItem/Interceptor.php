<?php
namespace Magento\Checkout\Controller\Sidebar\RemoveItem;

/**
 * Interceptor class for @see \Magento\Checkout\Controller\Sidebar\RemoveItem
 */
class Interceptor extends \Magento\Checkout\Controller\Sidebar\RemoveItem implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Sidebar $sidebar, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $sidebar, $logger, $jsonHelper, $resultPageFactory);
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
