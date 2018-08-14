<?php
namespace Aheadworks\RewardPoints\Controller\Share\Index;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Share\Index
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Share\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Aheadworks\RewardPoints\Api\ProductShareManagementInterface $productShareService)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $productShareService);
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
