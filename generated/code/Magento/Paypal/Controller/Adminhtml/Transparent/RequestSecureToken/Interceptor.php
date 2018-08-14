<?php
namespace Magento\Paypal\Controller\Adminhtml\Transparent\RequestSecureToken;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Adminhtml\Transparent\RequestSecureToken
 */
class Interceptor extends \Magento\Paypal\Controller\Adminhtml\Transparent\RequestSecureToken implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Session\Generic $sessionTransparent, \Magento\Paypal\Model\Payflow\Service\Request\SecureToken $secureTokenService, \Magento\Framework\Session\SessionManager $sessionManager, \Magento\Paypal\Model\Payflow\Transparent $transparent)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $sessionTransparent, $secureTokenService, $sessionManager, $transparent);
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
