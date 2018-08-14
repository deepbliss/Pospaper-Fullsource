<?php
namespace Magento\AdminNotification\Controller\Adminhtml\System\Message\ListAction;

/**
 * Interceptor class for @see \Magento\AdminNotification\Controller\Adminhtml\System\Message\ListAction
 */
class Interceptor extends \Magento\AdminNotification\Controller\Adminhtml\System\Message\ListAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\AdminNotification\Model\ResourceModel\System\Message\Collection $messageCollection)
    {
        $this->___init();
        parent::__construct($context, $jsonHelper, $messageCollection);
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
