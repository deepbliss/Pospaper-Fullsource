<?php
namespace Mconnectsolutions\Trackorder\Controller\Index\Index;

/**
 * Interceptor class for @see \Mconnectsolutions\Trackorder\Controller\Index\Index
 */
class Interceptor extends \Mconnectsolutions\Trackorder\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Mconnectsolutions\Trackorder\Block\Trackorder $trackUrl, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $trackUrl, $resultPageFactory);
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
