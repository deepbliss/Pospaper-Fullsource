<?php
namespace Magento\Theme\Controller\Adminhtml\Design\Config\Save;

/**
 * Interceptor class for @see \Magento\Theme\Controller\Adminhtml\Design\Config\Save
 */
class Interceptor extends \Magento\Theme\Controller\Adminhtml\Design\Config\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Theme\Model\DesignConfigRepository $designConfigRepository, \Magento\Theme\Model\Data\Design\ConfigFactory $configFactory, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor)
    {
        $this->___init();
        parent::__construct($context, $designConfigRepository, $configFactory, $dataPersistor);
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
