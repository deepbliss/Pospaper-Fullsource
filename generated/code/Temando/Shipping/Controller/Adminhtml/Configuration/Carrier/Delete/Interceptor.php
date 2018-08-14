<?php
namespace Temando\Shipping\Controller\Adminhtml\Configuration\Carrier\Delete;

/**
 * Interceptor class for @see \Temando\Shipping\Controller\Adminhtml\Configuration\Carrier\Delete
 */
class Interceptor extends \Temando\Shipping\Controller\Adminhtml\Configuration\Carrier\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Temando\Shipping\Model\ResourceModel\Repository\CarrierRepositoryInterface $carrierRepository)
    {
        $this->___init();
        parent::__construct($context, $carrierRepository);
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
