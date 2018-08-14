<?php
namespace Magento\Customer\Controller\Adminhtml\Index\InlineEdit;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Adminhtml\Index\InlineEdit
 */
class Interceptor extends \Magento\Customer\Controller\Adminhtml\Index\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Customer\Model\Customer\Mapper $customerMapper, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $customerRepository, $resultJsonFactory, $customerMapper, $dataObjectHelper, $logger);
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
