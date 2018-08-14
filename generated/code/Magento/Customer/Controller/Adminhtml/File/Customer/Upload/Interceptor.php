<?php
namespace Magento\Customer\Controller\Adminhtml\File\Customer\Upload;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Adminhtml\File\Customer\Upload
 */
class Interceptor extends \Magento\Customer\Controller\Adminhtml\File\Customer\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Customer\Model\FileUploaderFactory $fileUploaderFactory, \Magento\Customer\Api\CustomerMetadataInterface $customerMetadataService, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $fileUploaderFactory, $customerMetadataService, $logger);
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
