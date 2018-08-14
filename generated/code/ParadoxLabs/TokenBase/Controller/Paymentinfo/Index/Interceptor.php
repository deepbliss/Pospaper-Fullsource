<?php
namespace ParadoxLabs\TokenBase\Controller\Paymentinfo\Index;

/**
 * Interceptor class for @see \ParadoxLabs\TokenBase\Controller\Paymentinfo\Index
 */
class Interceptor extends \ParadoxLabs\TokenBase\Controller\Paymentinfo\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Framework\Registry $registry, \ParadoxLabs\TokenBase\Model\CardFactory $cardFactory, \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository, \ParadoxLabs\TokenBase\Helper\Data $helper, \ParadoxLabs\TokenBase\Helper\Address $addressHelper)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $resultPageFactory, $formKeyValidator, $registry, $cardFactory, $cardRepository, $helper, $addressHelper);
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
