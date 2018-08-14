<?php
namespace Magento\Paypal\Controller\Adminhtml\Paypal\Reports\Grid;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Adminhtml\Paypal\Reports\Grid
 */
class Interceptor extends \Magento\Paypal\Controller\Adminhtml\Paypal\Reports\Grid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Paypal\Model\Report\Settlement\RowFactory $rowFactory, \Magento\Paypal\Model\Report\SettlementFactory $settlementFactory, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $rowFactory, $settlementFactory, $logger);
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
