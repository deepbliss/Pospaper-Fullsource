<?php
namespace Magento\Integration\Controller\Token\Request;

/**
 * Interceptor class for @see \Magento\Integration\Controller\Token\Request
 */
class Interceptor extends \Magento\Integration\Controller\Token\Request implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Oauth\OauthInterface $oauthService, \Magento\Framework\Oauth\Helper\Request $helper)
    {
        $this->___init();
        parent::__construct($context, $oauthService, $helper);
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
