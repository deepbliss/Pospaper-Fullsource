<?php
namespace Magento\PageCache\Controller\Block\Esi;

/**
 * Interceptor class for @see \Magento\PageCache\Controller\Block\Esi
 */
class Interceptor extends \Magento\PageCache\Controller\Block\Esi implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Translate\InlineInterface $translateInline, \Magento\Framework\Serialize\Serializer\Json $jsonSerializer = null, \Magento\Framework\Serialize\Serializer\Base64Json $base64jsonSerializer = null)
    {
        $this->___init();
        parent::__construct($context, $translateInline, $jsonSerializer, $base64jsonSerializer);
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
