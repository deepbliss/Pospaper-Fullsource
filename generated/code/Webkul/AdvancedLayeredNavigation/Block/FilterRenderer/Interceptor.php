<?php
namespace Webkul\AdvancedLayeredNavigation\Block\FilterRenderer;

/**
 * Interceptor class for @see \Webkul\AdvancedLayeredNavigation\Block\FilterRenderer
 */
class Interceptor extends \Webkul\AdvancedLayeredNavigation\Block\FilterRenderer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Session\SessionManager $session, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $session, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'render');
        if (!$pluginInfo) {
            return parent::render($filter);
        } else {
            return $this->___callPlugins('render', func_get_args(), $pluginInfo);
        }
    }
}
