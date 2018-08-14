<?php
namespace Magento\User\Model\User;

/**
 * Interceptor class for @see \Magento\User\Model\User
 */
class Interceptor extends \Magento\User\Model\User implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\User\Helper\Data $userData, \Magento\Backend\App\ConfigInterface $config, \Magento\Framework\Validator\DataObjectFactory $validatorObjectFactory, \Magento\Authorization\Model\RoleFactory $roleFactory, \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\User\Model\UserValidationRules $validationRules, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $userData, $config, $validatorObjectFactory, $roleFactory, $transportBuilder, $encryptor, $storeManager, $validationRules, $resource, $resourceCollection, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
