<?php
namespace Magento\Theme\Controller\Adminhtml\Design\Config\FileUploader\Save;

/**
 * Interceptor class for @see \Magento\Theme\Controller\Adminhtml\Design\Config\FileUploader\Save
 */
class Interceptor extends \Magento\Theme\Controller\Adminhtml\Design\Config\FileUploader\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Theme\Model\Design\Config\FileUploader\FileProcessor $fileProcessor)
    {
        $this->___init();
        parent::__construct($context, $fileProcessor);
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
