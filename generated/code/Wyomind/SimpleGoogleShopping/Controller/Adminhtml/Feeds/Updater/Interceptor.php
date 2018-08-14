<?php
namespace Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Updater;

/**
 * Interceptor class for @see \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Updater
 */
class Interceptor extends \Wyomind\SimpleGoogleShopping\Controller\Adminhtml\Feeds\Updater implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Json\DecoderInterface $jsonDecoder, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds\Renderer\Status $renderer)
    {
        $this->___init();
        parent::__construct($context, $jsonDecoder, $jsonEncoder, $renderer);
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
