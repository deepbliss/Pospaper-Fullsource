<?php
namespace Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit;

/**
 * Interceptor class for @see \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit
 */
class Interceptor extends \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\PostDataProcessor $dataProcessor, \Aheadworks\RewardPoints\Api\TransactionRepositoryInterface $transactionRepository, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $transactionRepository, $jsonFactory, $dataObjectHelper);
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
