<?php
namespace Magento\Email\Model\Transport;

/**
 * Interceptor class for @see \Magento\Email\Model\Transport
 */
class Interceptor extends \Magento\Email\Model\Transport implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Zend_Mail_Transport_Sendmail $transport, \Magento\Framework\Mail\MessageInterface $message, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->___init();
        parent::__construct($transport, $message, $scopeConfig);
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
