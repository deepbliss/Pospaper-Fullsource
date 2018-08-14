<?php
namespace Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Value;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Value
 */
class Interceptor extends \Dotdigitalgroup\Email\Controller\Adminhtml\Rules\Value implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\Adminhtml\Source\Rules\Value $ruleValue, \Magento\Framework\Json\Helper\Data $jsonEncoder, \Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http $http, \Magento\Framework\Escaper $escaper)
    {
        $this->___init();
        parent::__construct($ruleValue, $jsonEncoder, $context, $http, $escaper);
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
