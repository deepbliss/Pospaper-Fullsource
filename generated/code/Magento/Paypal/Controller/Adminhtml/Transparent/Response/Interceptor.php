<?php
namespace Magento\Paypal\Controller\Adminhtml\Transparent\Response;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Adminhtml\Transparent\Response
 */
class Interceptor extends \Magento\Paypal\Controller\Adminhtml\Transparent\Response implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Paypal\Model\Payflow\Service\Response\Transaction $transaction, \Magento\Paypal\Model\Payflow\Service\Response\Validator\ResponseValidator $responseValidator, \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory, \Magento\Paypal\Model\Payflow\Transparent $transparent)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $transaction, $responseValidator, $resultLayoutFactory, $transparent);
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
