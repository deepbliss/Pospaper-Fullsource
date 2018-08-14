<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Edit;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Edit
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Rules $rulesResource, \Magento\Backend\App\Action\Context $context, \Dotdigitalgroup\Email\Model\Rules $rules, \Magento\Framework\Registry $registry, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($rulesResource, $context, $rules, $registry, $escaper);
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
