<?php
namespace Magento\Sales\Controller\Guest\PrintCreditmemo;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Guest\PrintCreditmemo
 */
class Interceptor extends \Magento\Sales\Controller\Guest\PrintCreditmemo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Controller\Guest\OrderViewAuthorization $orderAuthorization, \Magento\Framework\Registry $registry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository, \Magento\Sales\Controller\Guest\OrderLoader $orderLoader)
    {
        $this->___init();
        parent::__construct($context, $orderAuthorization, $registry, $resultPageFactory, $creditmemoRepository, $orderLoader);
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
