<?php
namespace Magento\PageCache\Controller\Adminhtml\PageCache\ExportVarnishConfig;

/**
 * Interceptor class for @see \Magento\PageCache\Controller\Adminhtml\PageCache\ExportVarnishConfig
 */
class Interceptor extends \Magento\PageCache\Controller\Adminhtml\PageCache\ExportVarnishConfig implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\PageCache\Model\Config $config)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $config);
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
