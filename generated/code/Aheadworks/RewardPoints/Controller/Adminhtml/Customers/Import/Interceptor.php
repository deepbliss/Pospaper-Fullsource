<?php
namespace Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Import;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Import
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Import implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\File\Csv $csvProcessor, \Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface $customerRewardPointsService, \Aheadworks\RewardPoints\Model\Import\PointsSummary $importPointsSummary)
    {
        $this->___init();
        parent::__construct($context, $csvProcessor, $customerRewardPointsService, $importPointsSummary);
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
