<?php
namespace Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\Save;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\Save
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\PostDataProcessor $dataProcessor, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor, \Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface $customerRewardPointsService, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $dataPersistor, $customerRewardPointsService, $dataObjectHelper);
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
