<?php
namespace Magento\Framework\Mail\Transport;

/**
 * Interceptor class for @see \Magento\Framework\Mail\Transport
 */
class Interceptor extends \Magento\Framework\Mail\Transport implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Mail\MessageInterface $message, $parameters = null)
    {
        $this->___init();
        parent::__construct($message, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sendMessage');
        if (!$pluginInfo) {
            return parent::sendMessage();
        } else {
            return $this->___callPlugins('sendMessage', func_get_args(), $pluginInfo);
        }
    }
}
