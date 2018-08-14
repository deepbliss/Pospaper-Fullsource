<?php
namespace Magento\Captcha\Controller\Adminhtml\Refresh\Refresh;

/**
 * Interceptor class for @see \Magento\Captcha\Controller\Adminhtml\Refresh\Refresh
 */
class Interceptor extends \Magento\Captcha\Controller\Adminhtml\Refresh\Refresh implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Captcha\Helper\Data $captchaHelper, \Magento\Framework\Serialize\Serializer\Json $serializer)
    {
        $this->___init();
        parent::__construct($context, $captchaHelper, $serializer);
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
