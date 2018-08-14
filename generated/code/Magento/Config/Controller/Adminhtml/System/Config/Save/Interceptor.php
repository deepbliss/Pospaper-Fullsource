<?php
namespace Magento\Config\Controller\Adminhtml\System\Config\Save;

/**
 * Interceptor class for @see \Magento\Config\Controller\Adminhtml\System\Config\Save
 */
class Interceptor extends \Magento\Config\Controller\Adminhtml\System\Config\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Config\Model\Config\Structure $configStructure, \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker, \Magento\Config\Model\Config\Factory $configFactory, \Magento\Framework\Cache\FrontendInterface $cache, \Magento\Framework\Stdlib\StringUtils $string)
    {
        $this->___init();
        parent::__construct($context, $configStructure, $sectionChecker, $configFactory, $cache, $string);
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
