<?php
namespace Temando\Shipping\Controller\Adminhtml\Settings\Advanced\Save;

/**
 * Interceptor class for @see \Temando\Shipping\Controller\Adminhtml\Settings\Advanced\Save
 */
class Interceptor extends \Temando\Shipping\Controller\Adminhtml\Settings\Advanced\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Temando\Shipping\Model\Config\ModuleConfigInterface $config, \Temando\Shipping\Model\ResourceModel\EventStream\StreamRepositoryInterface $streamRepository)
    {
        $this->___init();
        parent::__construct($context, $config, $streamRepository);
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
