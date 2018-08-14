<?php
namespace Magento\Customer\Controller\Adminhtml\Index\Index;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Adminhtml\Index\Index
 */
class Interceptor extends \Magento\Customer\Controller\Adminhtml\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Customer\Model\AddressFactory $addressFactory, \Magento\Customer\Model\Metadata\FormFactory $formFactory, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory, \Magento\Customer\Helper\View $viewHelper, \Magento\Framework\Math\Random $random, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter, \Magento\Customer\Model\Address\Mapper $addressMapper, \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory, \Magento\Customer\Model\Customer\Mapper $customerMapper, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\DataObjectFactory $objectFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $fileFactory, $customerFactory, $addressFactory, $formFactory, $subscriberFactory, $viewHelper, $random, $customerRepository, $extensibleDataObjectConverter, $addressMapper, $customerAccountManagement, $addressRepository, $customerDataFactory, $addressDataFactory, $customerMapper, $dataObjectProcessor, $dataObjectHelper, $objectFactory, $layoutFactory, $resultLayoutFactory, $resultPageFactory, $resultForwardFactory, $resultJsonFactory);
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
