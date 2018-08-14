<?php
namespace Magento\Widget\Controller\Adminhtml\Widget\Instance\NewAction;

/**
 * Interceptor class for @see \Magento\Widget\Controller\Adminhtml\Widget\Instance\NewAction
 */
class Interceptor extends \Magento\Widget\Controller\Adminhtml\Widget\Instance\NewAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Math\Random $mathRandom, \Magento\Framework\Translate\InlineInterface $translateInline)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $widgetFactory, $logger, $mathRandom, $translateInline);
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
