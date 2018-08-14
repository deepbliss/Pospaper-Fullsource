<?php
namespace Magento\Customer\Controller\Adminhtml\Index\MassDelete;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Adminhtml\Index\MassDelete
 */
class Interceptor extends \Magento\Customer\Controller\Adminhtml\Index\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $collectionFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $customerRepository);
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
