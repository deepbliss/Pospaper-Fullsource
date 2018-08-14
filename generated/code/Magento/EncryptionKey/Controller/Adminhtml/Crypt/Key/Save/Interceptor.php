<?php
namespace Magento\EncryptionKey\Controller\Adminhtml\Crypt\Key\Save;

/**
 * Interceptor class for @see \Magento\EncryptionKey\Controller\Adminhtml\Crypt\Key\Save
 */
class Interceptor extends \Magento\EncryptionKey\Controller\Adminhtml\Crypt\Key\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Magento\EncryptionKey\Model\ResourceModel\Key\Change $change, \Magento\Framework\App\CacheInterface $cache)
    {
        $this->___init();
        parent::__construct($context, $encryptor, $change, $cache);
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
