<?php
namespace Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Generate;

/**
 * Interceptor class for @see \Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Generate
 */
class Interceptor extends \Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Generate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, \Magento\SalesRule\Model\CouponGenerator $couponGenerator = null)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter, $couponGenerator);
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
