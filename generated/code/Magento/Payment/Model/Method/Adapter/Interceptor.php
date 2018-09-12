<?php
namespace Magento\Payment\Model\Method\Adapter;

/**
 * Interceptor class for @see \Magento\Payment\Model\Method\Adapter
 */
class Interceptor extends \Magento\Payment\Model\Method\Adapter implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Payment\Gateway\Config\ValueHandlerPoolInterface $valueHandlerPool, \Magento\Payment\Gateway\Data\PaymentDataObjectFactory $paymentDataObjectFactory, $code, $formBlockType, $infoBlockType, \Magento\Payment\Gateway\Command\CommandPoolInterface $commandPool = null, \Magento\Payment\Gateway\Validator\ValidatorPoolInterface $validatorPool = null, \Magento\Payment\Gateway\Command\CommandManagerInterface $commandExecutor = null, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->___init();
        parent::__construct($eventManager, $valueHandlerPool, $paymentDataObjectFactory, $code, $formBlockType, $infoBlockType, $commandPool, $validatorPool, $commandExecutor, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getValidatorPool()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getValidatorPool');
        if (!$pluginInfo) {
            return parent::getValidatorPool();
        } else {
            return $this->___callPlugins('getValidatorPool', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canOrder()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canOrder');
        if (!$pluginInfo) {
            return parent::canOrder();
        } else {
            return $this->___callPlugins('canOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canAuthorize()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canAuthorize');
        if (!$pluginInfo) {
            return parent::canAuthorize();
        } else {
            return $this->___callPlugins('canAuthorize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCapture()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canCapture');
        if (!$pluginInfo) {
            return parent::canCapture();
        } else {
            return $this->___callPlugins('canCapture', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCapturePartial()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canCapturePartial');
        if (!$pluginInfo) {
            return parent::canCapturePartial();
        } else {
            return $this->___callPlugins('canCapturePartial', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canCaptureOnce()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canCaptureOnce');
        if (!$pluginInfo) {
            return parent::canCaptureOnce();
        } else {
            return $this->___callPlugins('canCaptureOnce', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canRefund()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canRefund');
        if (!$pluginInfo) {
            return parent::canRefund();
        } else {
            return $this->___callPlugins('canRefund', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canRefundPartialPerInvoice()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canRefundPartialPerInvoice');
        if (!$pluginInfo) {
            return parent::canRefundPartialPerInvoice();
        } else {
            return $this->___callPlugins('canRefundPartialPerInvoice', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canVoid()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canVoid');
        if (!$pluginInfo) {
            return parent::canVoid();
        } else {
            return $this->___callPlugins('canVoid', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUseInternal()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canUseInternal');
        if (!$pluginInfo) {
            return parent::canUseInternal();
        } else {
            return $this->___callPlugins('canUseInternal', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUseCheckout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canUseCheckout');
        if (!$pluginInfo) {
            return parent::canUseCheckout();
        } else {
            return $this->___callPlugins('canUseCheckout', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canEdit()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canEdit');
        if (!$pluginInfo) {
            return parent::canEdit();
        } else {
            return $this->___callPlugins('canEdit', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canFetchTransactionInfo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canFetchTransactionInfo');
        if (!$pluginInfo) {
            return parent::canFetchTransactionInfo();
        } else {
            return $this->___callPlugins('canFetchTransactionInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canReviewPayment()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canReviewPayment');
        if (!$pluginInfo) {
            return parent::canReviewPayment();
        } else {
            return $this->___callPlugins('canReviewPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isGateway()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isGateway');
        if (!$pluginInfo) {
            return parent::isGateway();
        } else {
            return $this->___callPlugins('isGateway', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOffline()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isOffline');
        if (!$pluginInfo) {
            return parent::isOffline();
        } else {
            return $this->___callPlugins('isOffline', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isInitializeNeeded()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isInitializeNeeded');
        if (!$pluginInfo) {
            return parent::isInitializeNeeded();
        } else {
            return $this->___callPlugins('isInitializeNeeded', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isAvailable');
        if (!$pluginInfo) {
            return parent::isAvailable($quote);
        } else {
            return $this->___callPlugins('isAvailable', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isActive($storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isActive');
        if (!$pluginInfo) {
            return parent::isActive($storeId);
        } else {
            return $this->___callPlugins('isActive', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUseForCountry($country)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canUseForCountry');
        if (!$pluginInfo) {
            return parent::canUseForCountry($country);
        } else {
            return $this->___callPlugins('canUseForCountry', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function canUseForCurrency($currencyCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canUseForCurrency');
        if (!$pluginInfo) {
            return parent::canUseForCurrency($currencyCode);
        } else {
            return $this->___callPlugins('canUseForCurrency', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigData($field, $storeId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigData');
        if (!$pluginInfo) {
            return parent::getConfigData($field, $storeId);
        } else {
            return $this->___callPlugins('getConfigData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate();
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTransactionInfo(\Magento\Payment\Model\InfoInterface $payment, $transactionId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'fetchTransactionInfo');
        if (!$pluginInfo) {
            return parent::fetchTransactionInfo($payment, $transactionId);
        } else {
            return $this->___callPlugins('fetchTransactionInfo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function order(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'order');
        if (!$pluginInfo) {
            return parent::order($payment, $amount);
        } else {
            return $this->___callPlugins('order', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'authorize');
        if (!$pluginInfo) {
            return parent::authorize($payment, $amount);
        } else {
            return $this->___callPlugins('authorize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'capture');
        if (!$pluginInfo) {
            return parent::capture($payment, $amount);
        } else {
            return $this->___callPlugins('capture', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'refund');
        if (!$pluginInfo) {
            return parent::refund($payment, $amount);
        } else {
            return $this->___callPlugins('refund', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cancel(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cancel');
        if (!$pluginInfo) {
            return parent::cancel($payment);
        } else {
            return $this->___callPlugins('cancel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function void(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'void');
        if (!$pluginInfo) {
            return parent::void($payment);
        } else {
            return $this->___callPlugins('void', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function acceptPayment(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'acceptPayment');
        if (!$pluginInfo) {
            return parent::acceptPayment($payment);
        } else {
            return $this->___callPlugins('acceptPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function denyPayment(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'denyPayment');
        if (!$pluginInfo) {
            return parent::denyPayment($payment);
        } else {
            return $this->___callPlugins('denyPayment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCode');
        if (!$pluginInfo) {
            return parent::getCode();
        } else {
            return $this->___callPlugins('getCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getTitle');
        if (!$pluginInfo) {
            return parent::getTitle();
        } else {
            return $this->___callPlugins('getTitle', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStore($storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setStore');
        if (!$pluginInfo) {
            return parent::setStore($storeId);
        } else {
            return $this->___callPlugins('setStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStore');
        if (!$pluginInfo) {
            return parent::getStore();
        } else {
            return $this->___callPlugins('getStore', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBlockType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFormBlockType');
        if (!$pluginInfo) {
            return parent::getFormBlockType();
        } else {
            return $this->___callPlugins('getFormBlockType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInfoBlockType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInfoBlockType');
        if (!$pluginInfo) {
            return parent::getInfoBlockType();
        } else {
            return $this->___callPlugins('getInfoBlockType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInfoInstance()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInfoInstance');
        if (!$pluginInfo) {
            return parent::getInfoInstance();
        } else {
            return $this->___callPlugins('getInfoInstance', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setInfoInstance(\Magento\Payment\Model\InfoInterface $info)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setInfoInstance');
        if (!$pluginInfo) {
            return parent::setInfoInstance($info);
        } else {
            return $this->___callPlugins('setInfoInstance', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'assignData');
        if (!$pluginInfo) {
            return parent::assignData($data);
        } else {
            return $this->___callPlugins('assignData', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initialize($paymentAction, $stateObject)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'initialize');
        if (!$pluginInfo) {
            return parent::initialize($paymentAction, $stateObject);
        } else {
            return $this->___callPlugins('initialize', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigPaymentAction()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigPaymentAction');
        if (!$pluginInfo) {
            return parent::getConfigPaymentAction();
        } else {
            return $this->___callPlugins('getConfigPaymentAction', func_get_args(), $pluginInfo);
        }
    }
}
