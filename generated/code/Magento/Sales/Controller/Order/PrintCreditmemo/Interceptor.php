<?php
namespace Magento\Sales\Controller\Order\PrintCreditmemo;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Order\PrintCreditmemo
 */
class Interceptor extends \Magento\Sales\Controller\Order\PrintCreditmemo implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Controller\AbstractController\OrderViewAuthorizationInterface $orderAuthorization, \Magento\Framework\Registry $registry, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository)
    {
        $this->___init();
        parent::__construct($context, $orderAuthorization, $registry, $resultPageFactory, $creditmemoRepository);
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
