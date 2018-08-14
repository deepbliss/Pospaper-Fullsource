<?php
namespace Nextopia\Search\Controller\Index\Index;

/**
 * Interceptor class for @see \Nextopia\Search\Controller\Index\Index
 */
class Interceptor extends \Nextopia\Search\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Nextopia\Search\Helper\Data $nsearchHelperData)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $nsearchHelperData);
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
