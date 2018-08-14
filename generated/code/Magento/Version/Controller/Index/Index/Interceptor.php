<?php
namespace Magento\Version\Controller\Index\Index;

/**
 * Interceptor class for @see \Magento\Version\Controller\Index\Index
 */
class Interceptor extends \Magento\Version\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\ProductMetadataInterface $productMetadata)
    {
        $this->___init();
        parent::__construct($context, $productMetadata);
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
