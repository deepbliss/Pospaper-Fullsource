<?php
namespace Wyomind\SimpleGoogleShopping\Controller\Feeds\Generate;

/**
 * Interceptor class for @see \Wyomind\SimpleGoogleShopping\Controller\Feeds\Generate
 */
class Interceptor extends \Wyomind\SimpleGoogleShopping\Controller\Feeds\Generate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Wyomind\Core\Helper\Data $coreHelper)
    {
        $this->___init();
        parent::__construct($context, $resultRawFactory, $coreHelper);
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
