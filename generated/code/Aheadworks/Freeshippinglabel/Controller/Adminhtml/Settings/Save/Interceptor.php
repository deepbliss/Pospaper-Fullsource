<?php
namespace Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Save;

/**
 * Interceptor class for @see \Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Save
 */
class Interceptor extends \Aheadworks\Freeshippinglabel\Controller\Adminhtml\Settings\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface $labelRepository, \Aheadworks\Freeshippinglabel\Api\Data\LabelInterfaceFactory $labelDataFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor)
    {
        $this->___init();
        parent::__construct($context, $labelRepository, $labelDataFactory, $dataObjectHelper, $dataPersistor);
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
