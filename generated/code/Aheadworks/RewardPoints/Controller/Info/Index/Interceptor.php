<?php
namespace Aheadworks\RewardPoints\Controller\Info\Index;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Info\Index
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Info\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface $customerRewardPointsService)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $customerRewardPointsService);
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
