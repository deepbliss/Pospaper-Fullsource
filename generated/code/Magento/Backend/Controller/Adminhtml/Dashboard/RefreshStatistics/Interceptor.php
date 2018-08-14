<?php
namespace Magento\Backend\Controller\Adminhtml\Dashboard\RefreshStatistics;

/**
 * Interceptor class for @see \Magento\Backend\Controller\Adminhtml\Dashboard\RefreshStatistics
 */
class Interceptor extends \Magento\Backend\Controller\Adminhtml\Dashboard\RefreshStatistics implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, array $reportTypes, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $dateFilter, $reportTypes, $logger);
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
