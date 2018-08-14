<?php
namespace Magento\Customer\Model\AccountManagement;

/**
 * Interceptor class for @see \Magento\Customer\Model\AccountManagement
 */
class Interceptor extends \Magento\Customer\Model\AccountManagement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Math\Random $mathRandom, \Magento\Customer\Model\Metadata\Validator $validator, \Magento\Customer\Api\Data\ValidationResultsInterfaceFactory $validationResultsDataFactory, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Customer\Api\CustomerMetadataInterface $customerMetadataService, \Magento\Customer\Model\CustomerRegistry $customerRegistry, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Magento\Customer\Model\Config\Share $configShare, \Magento\Framework\Stdlib\StringUtils $stringHelper, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Reflection\DataObjectProcessor $dataProcessor, \Magento\Framework\Registry $registry, \Magento\Customer\Helper\View $customerViewHelper, \Magento\Framework\Stdlib\DateTime $dateTime, \Magento\Customer\Model\Customer $customerModel, \Magento\Framework\DataObjectFactory $objectFactory, \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter, \Magento\Customer\Model\Customer\CredentialsValidator $credentialsValidator = null, \Magento\Framework\Intl\DateTimeFactory $dateTimeFactory = null, \Magento\Framework\Session\SessionManagerInterface $sessionManager = null, \Magento\Framework\Session\SaveHandlerInterface $saveHandler = null, \Magento\Customer\Model\ResourceModel\Visitor\CollectionFactory $visitorCollectionFactory = null)
    {
        $this->___init();
        parent::__construct($customerFactory, $eventManager, $storeManager, $mathRandom, $validator, $validationResultsDataFactory, $addressRepository, $customerMetadataService, $customerRegistry, $logger, $encryptor, $configShare, $stringHelper, $customerRepository, $scopeConfig, $transportBuilder, $dataProcessor, $registry, $customerViewHelper, $dateTime, $customerModel, $objectFactory, $extensibleDataObjectConverter, $credentialsValidator, $dateTimeFactory, $sessionManager, $saveHandler, $visitorCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function initiatePasswordReset($email, $template, $websiteId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'initiatePasswordReset');
        if (!$pluginInfo) {
            return parent::initiatePasswordReset($email, $template, $websiteId);
        } else {
            return $this->___callPlugins('initiatePasswordReset', func_get_args(), $pluginInfo);
        }
    }
}
