<?php
namespace Magento\Sales\Controller\Adminhtml\Order\MassHold;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\MassHold
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\MassHold implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory, \Magento\Sales\Api\OrderManagementInterface $orderManagement)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $orderManagement);
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
