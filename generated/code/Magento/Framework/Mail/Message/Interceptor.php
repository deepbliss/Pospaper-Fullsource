<?php
namespace Magento\Framework\Mail\Message;

/**
 * Interceptor class for @see \Magento\Framework\Mail\Message
 */
class Interceptor extends \Magento\Framework\Mail\Message implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($charset = 'utf-8')
    {
        $this->___init();
        parent::__construct($charset);
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBody');
        if (!$pluginInfo) {
            return parent::setBody($body);
        } else {
            return $this->___callPlugins('setBody', func_get_args(), $pluginInfo);
        }
    }
}
