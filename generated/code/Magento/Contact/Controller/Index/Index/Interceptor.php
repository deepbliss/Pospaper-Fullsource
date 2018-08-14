<?php
namespace Magento\Contact\Controller\Index\Index;

/**
 * Interceptor class for @see \Magento\Contact\Controller\Index\Index
 */
class Interceptor extends \Magento\Contact\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Contact\Model\ConfigInterface $contactsConfig)
    {
        $this->___init();
        parent::__construct($context, $contactsConfig);
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
