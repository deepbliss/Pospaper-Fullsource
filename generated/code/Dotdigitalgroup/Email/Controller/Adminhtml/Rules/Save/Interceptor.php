<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Save;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Save
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Rules $rulesResource, \Magento\Backend\App\Action\Context $context, \Dotdigitalgroup\Email\Model\RulesFactory $rulesFactory, \Magento\Store\Model\StoreManagerInterface $storeManagerInterface, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($rulesResource, $context, $rulesFactory, $storeManagerInterface, $escaper);
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
