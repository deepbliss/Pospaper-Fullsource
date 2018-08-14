<?php
namespace Magento\Vault\Controller\Cards\DeleteAction;

/**
 * Interceptor class for @see \Magento\Vault\Controller\Cards\DeleteAction
 */
class Interceptor extends \Magento\Vault\Controller\Cards\DeleteAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Framework\Data\Form\FormKey\Validator $fkValidator, \Magento\Vault\Api\PaymentTokenRepositoryInterface $tokenRepository, \Magento\Vault\Model\PaymentTokenManagement $paymentTokenManagement)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $jsonFactory, $fkValidator, $tokenRepository, $paymentTokenManagement);
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
