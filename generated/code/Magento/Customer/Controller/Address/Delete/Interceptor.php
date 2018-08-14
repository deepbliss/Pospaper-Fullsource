<?php
namespace Magento\Customer\Controller\Address\Delete;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Address\Delete
 */
class Interceptor extends \Magento\Customer\Controller\Address\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Customer\Model\Metadata\FormFactory $formFactory, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory, \Magento\Customer\Api\Data\RegionInterfaceFactory $regionDataFactory, \Magento\Framework\Reflection\DataObjectProcessor $dataProcessor, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $formKeyValidator, $formFactory, $addressRepository, $addressDataFactory, $regionDataFactory, $dataProcessor, $dataObjectHelper, $resultForwardFactory, $resultPageFactory);
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
