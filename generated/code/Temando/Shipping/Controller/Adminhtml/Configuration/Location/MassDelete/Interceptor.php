<?php
namespace Temando\Shipping\Controller\Adminhtml\Configuration\Location\MassDelete;

/**
 * Interceptor class for @see \Temando\Shipping\Controller\Adminhtml\Configuration\Location\MassDelete
 */
class Interceptor extends \Temando\Shipping\Controller\Adminhtml\Configuration\Location\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Temando\Shipping\Model\ResourceModel\Repository\LocationRepositoryInterface $locationRepository, \Temando\Shipping\Ui\Component\MassAction\Filter $filter)
    {
        $this->___init();
        parent::__construct($context, $locationRepository, $filter);
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
