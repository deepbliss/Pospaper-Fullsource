<?php
namespace Magento\Tax\Controller\Adminhtml\Rate\AjaxDelete;

/**
 * Interceptor class for @see \Magento\Tax\Controller\Adminhtml\Rate\AjaxDelete
 */
class Interceptor extends \Magento\Tax\Controller\Adminhtml\Rate\AjaxDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Tax\Model\Calculation\Rate\Converter $taxRateConverter, \Magento\Tax\Api\TaxRateRepositoryInterface $taxRateRepository)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $taxRateConverter, $taxRateRepository);
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
