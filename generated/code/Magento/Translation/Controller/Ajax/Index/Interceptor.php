<?php
namespace Magento\Translation\Controller\Ajax\Index;

/**
 * Interceptor class for @see \Magento\Translation\Controller\Ajax\Index
 */
class Interceptor extends \Magento\Translation\Controller\Ajax\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Translate\Inline\ParserInterface $inlineParser)
    {
        $this->___init();
        parent::__construct($context, $inlineParser);
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
