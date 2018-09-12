<?php
namespace Magento\PageCache\Observer\FlushFormKey;

/**
 * Interceptor class for @see \Magento\PageCache\Observer\FlushFormKey
 */
class Interceptor extends \Magento\PageCache\Observer\FlushFormKey implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\PageCache\FormKey $cookieFormKey, \Magento\Framework\Data\Form\FormKey $dataFormKey)
    {
        $this->___init();
        parent::__construct($cookieFormKey, $dataFormKey);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($observer);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
