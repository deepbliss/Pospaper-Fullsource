<?php
namespace Magestore\Bannerslider\Controller\Index\Click;

/**
 * Interceptor class for @see \Magestore\Bannerslider\Controller\Index\Click
 */
class Interceptor extends \Magestore\Bannerslider\Controller\Index\Click implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magestore\Bannerslider\Model\SliderFactory $sliderFactory, \Magestore\Bannerslider\Model\BannerFactory $bannerFactory, \Magestore\Bannerslider\Model\ReportFactory $reportFactory, \Magestore\Bannerslider\Model\ResourceModel\Report\CollectionFactory $reportCollectionFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\Logger\Monolog $monolog, \Magento\Framework\Stdlib\DateTime\Timezone $stdTimezone)
    {
        $this->___init();
        parent::__construct($context, $sliderFactory, $bannerFactory, $reportFactory, $reportCollectionFactory, $resultRawFactory, $monolog, $stdTimezone);
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
