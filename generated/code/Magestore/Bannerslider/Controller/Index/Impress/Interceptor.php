<?php
namespace Magestore\Bannerslider\Controller\Index\Impress;

/**
 * Interceptor class for @see \Magestore\Bannerslider\Controller\Index\Impress
 */
class Interceptor extends \Magestore\Bannerslider\Controller\Index\Impress implements \Magento\Framework\Interception\InterceptorInterface
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
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute();
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieManager()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCookieManager');
        if (!$pluginInfo) {
            return parent::getCookieManager();
        } else {
            return $this->___callPlugins('getCookieManager', func_get_args(), $pluginInfo);
        }
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

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        if (!$pluginInfo) {
            return parent::getActionFlag();
        } else {
            return $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        if (!$pluginInfo) {
            return parent::getRequest();
        } else {
            return $this->___callPlugins('getRequest', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        if (!$pluginInfo) {
            return parent::getResponse();
        } else {
            return $this->___callPlugins('getResponse', func_get_args(), $pluginInfo);
        }
    }
}
