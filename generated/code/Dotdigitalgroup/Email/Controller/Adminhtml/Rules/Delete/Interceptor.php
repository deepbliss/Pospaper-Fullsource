<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Delete;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Delete
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Rules $rulesResource, \Magento\Backend\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManagerInterface, \Dotdigitalgroup\Email\Model\Rules $rules, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($rulesResource, $context, $storeManagerInterface, $rules, $escaper);
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
