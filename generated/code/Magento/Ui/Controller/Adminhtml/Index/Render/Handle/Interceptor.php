<?php
namespace Magento\Ui\Controller\Adminhtml\Index\Render\Handle;

/**
 * Interceptor class for @see \Magento\Ui\Controller\Adminhtml\Index\Render\Handle
 */
class Interceptor extends \Magento\Ui\Controller\Adminhtml\Index\Render\Handle implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Element\UiComponentFactory $factory)
    {
        $this->___init();
        parent::__construct($context, $factory);
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
