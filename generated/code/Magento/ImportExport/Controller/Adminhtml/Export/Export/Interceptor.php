<?php
namespace Magento\ImportExport\Controller\Adminhtml\Export\Export;

/**
 * Interceptor class for @see \Magento\ImportExport\Controller\Adminhtml\Export\Export
 */
class Interceptor extends \Magento\ImportExport\Controller\Adminhtml\Export\Export implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Session\SessionManagerInterface $sessionManager = null)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $sessionManager);
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
