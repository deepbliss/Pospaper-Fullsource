<?php
namespace Magento\Email\Model\BackendTemplate;

/**
 * Interceptor class for @see \Magento\Email\Model\BackendTemplate
 */
class Interceptor extends \Magento\Email\Model\BackendTemplate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\View\DesignInterface $design, \Magento\Framework\Registry $registry, \Magento\Store\Model\App\Emulation $appEmulation, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Email\Model\Template\Config $emailConfig, \Magento\Email\Model\TemplateFactory $templateFactory, \Magento\Framework\Filter\FilterManager $filterManager, \Magento\Framework\UrlInterface $urlModel, \Magento\Email\Model\Template\FilterFactory $filterFactory, \Magento\Config\Model\Config\Structure $structure, array $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $design, $registry, $appEmulation, $storeManager, $assetRepo, $filesystem, $scopeConfig, $emailConfig, $templateFactory, $filterManager, $urlModel, $filterFactory, $structure, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'beforeSave');
        if (!$pluginInfo) {
            return parent::beforeSave();
        } else {
            return $this->___callPlugins('beforeSave', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getData($key = '', $index = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getData');
        if (!$pluginInfo) {
            return parent::getData($key, $index);
        } else {
            return $this->___callPlugins('getData', func_get_args(), $pluginInfo);
        }
    }
}
