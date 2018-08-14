<?php
namespace Magento\OfflineShipping\Controller\Adminhtml\System\Config\ExportTablerates;

/**
 * Interceptor class for @see \Magento\OfflineShipping\Controller\Adminhtml\System\Config\ExportTablerates
 */
class Interceptor extends \Magento\OfflineShipping\Controller\Adminhtml\System\Config\ExportTablerates implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Config\Model\Config\Structure $configStructure, \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $configStructure, $sectionChecker, $fileFactory, $storeManager);
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
