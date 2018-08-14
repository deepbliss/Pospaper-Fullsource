<?php
namespace Magento\Reports\Controller\Adminhtml\Report\Statistics\RefreshRecent;

/**
 * Interceptor class for @see \Magento\Reports\Controller\Adminhtml\Report\Statistics\RefreshRecent
 */
class Interceptor extends \Magento\Reports\Controller\Adminhtml\Report\Statistics\RefreshRecent implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, array $reportTypes)
    {
        $this->___init();
        parent::__construct($context, $dateFilter, $reportTypes);
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
