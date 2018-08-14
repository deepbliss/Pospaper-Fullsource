<?php
namespace Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Index;

/**
 * Interceptor class for @see \Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Index
 */
class Interceptor extends \Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface $labelRepository, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor)
    {
        $this->___init();
        parent::__construct($context, $labelRepository, $resultPageFactory, $coreRegistry, $dataObjectProcessor, $dataPersistor);
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
