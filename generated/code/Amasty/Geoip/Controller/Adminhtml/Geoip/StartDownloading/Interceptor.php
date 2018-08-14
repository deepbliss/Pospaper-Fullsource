<?php
namespace Amasty\Geoip\Controller\Adminhtml\Geoip\StartDownloading;

/**
 * Interceptor class for @see \Amasty\Geoip\Controller\Adminhtml\Geoip\StartDownloading
 */
class Interceptor extends \Amasty\Geoip\Controller\Adminhtml\Geoip\StartDownloading implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Amasty\Geoip\Model\Import $importModel, \Amasty\Geoip\Helper\Data $geoipHelper, \Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->___init();
        parent::__construct($context, $importModel, $geoipHelper, $jsonHelper);
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
