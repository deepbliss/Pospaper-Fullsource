<?php
namespace Magento\ReleaseNotification\Controller\Adminhtml\Notification\MarkUserNotified;

/**
 * Interceptor class for @see \Magento\ReleaseNotification\Controller\Adminhtml\Notification\MarkUserNotified
 */
class Interceptor extends \Magento\ReleaseNotification\Controller\Adminhtml\Notification\MarkUserNotified implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\ProductMetadataInterface $productMetadata, \Magento\ReleaseNotification\Model\ResourceModel\Viewer\Logger $notificationLogger, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $productMetadata, $notificationLogger, $logger);
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
