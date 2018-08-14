<?php
namespace Magento\Sales\Controller\Download\DownloadCustomOption;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Download\DownloadCustomOption
 */
class Interceptor extends \Magento\Sales\Controller\Download\DownloadCustomOption implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory, \Magento\Sales\Model\Download $download, \Magento\Framework\Unserialize\Unserialize $unserialize, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $download, $unserialize, $serializer);
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
