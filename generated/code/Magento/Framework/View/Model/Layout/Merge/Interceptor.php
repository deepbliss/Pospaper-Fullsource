<?php
namespace Magento\Framework\View\Model\Layout\Merge;

/**
 * Interceptor class for @see \Magento\Framework\View\Model\Layout\Merge
 */
class Interceptor extends \Magento\Framework\View\Model\Layout\Merge implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\DesignInterface $design, \Magento\Framework\Url\ScopeResolverInterface $scopeResolver, \Magento\Framework\View\File\CollectorInterface $fileSource, \Magento\Framework\View\File\CollectorInterface $pageLayoutFileSource, \Magento\Framework\App\State $appState, \Magento\Framework\Cache\FrontendInterface $cache, \Magento\Framework\View\Model\Layout\Update\Validator $validator, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Filesystem\File\ReadFactory $readFactory, \Magento\Framework\View\Design\ThemeInterface $theme = null, $cacheSuffix = '')
    {
        $this->___init();
        parent::__construct($design, $scopeResolver, $fileSource, $pageLayoutFileSource, $appState, $cache, $validator, $logger, $readFactory, $theme, $cacheSuffix);
    }

    /**
     * {@inheritdoc}
     */
    public function validateUpdate($handle, $updateXml)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validateUpdate');
        if (!$pluginInfo) {
            return parent::validateUpdate($handle, $updateXml);
        } else {
            return $this->___callPlugins('validateUpdate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDbUpdateString($handle)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDbUpdateString');
        if (!$pluginInfo) {
            return parent::getDbUpdateString($handle);
        } else {
            return $this->___callPlugins('getDbUpdateString', func_get_args(), $pluginInfo);
        }
    }
}
