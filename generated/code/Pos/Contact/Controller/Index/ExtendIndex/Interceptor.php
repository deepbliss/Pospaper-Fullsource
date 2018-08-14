<?php
namespace Pos\Contact\Controller\Index\ExtendIndex;

/**
 * Interceptor class for @see \Pos\Contact\Controller\Index\ExtendIndex
 */
class Interceptor extends \Pos\Contact\Controller\Index\ExtendIndex implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Contact\Model\ConfigInterface $contactsConfig, \Magento\Contact\Model\MailInterface $mail, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->___init();
        parent::__construct($context, $contactsConfig, $mail, $dataPersistor, $logger);
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
