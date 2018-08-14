<?php
namespace Magento\Review\Controller\Customer\View;

/**
 * Interceptor class for @see \Magento\Review\Controller\Customer\View
 */
class Interceptor extends \Magento\Review\Controller\Customer\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Review\Model\ReviewFactory $reviewFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $reviewFactory);
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
