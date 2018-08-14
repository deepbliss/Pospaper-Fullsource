<?php
namespace Magento\Newsletter\Model\Subscriber;

/**
 * Interceptor class for @see \Magento\Newsletter\Model\Subscriber
 */
class Interceptor extends \Magento\Newsletter\Model\Subscriber implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Newsletter\Helper\Data $newsletterData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement, \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Framework\Stdlib\DateTime\DateTime $dateTime = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $newsletterData, $scopeConfig, $transportBuilder, $storeManager, $customerSession, $customerRepository, $customerAccountManagement, $inlineTranslation, $resource, $resourceCollection, $data, $dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationSuccessEmail()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendConfirmationSuccessEmail');
        if (!$pluginInfo) {
            return parent::sendConfirmationSuccessEmail();
        } else {
            return $this->___callPlugins('sendConfirmationSuccessEmail', func_get_args(), $pluginInfo);
        }
    }
}
