<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Block\Adminhtml\Subscription\View\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Payment tab
 */
class Payment extends Generic implements TabInterface
{
    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * @var \Magento\Backend\Model\Url
     */
    protected $url;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Vault
     */
    protected $vaultHelper;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\Payment
     */
    protected $paymentService;

    /**
     * Payment tab constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     * @param \Magento\Backend\Model\Url $url
     * @param \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper
     * @param \ParadoxLabs\TokenBase\Helper\Address $addressHelper
     * @param \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService
     * @param array $data
     * @internal param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper,
        \Magento\Backend\Model\Url $url,
        \ParadoxLabs\Subscriptions\Helper\Vault $vaultHelper,
        \ParadoxLabs\TokenBase\Helper\Address $addressHelper,
        \ParadoxLabs\Subscriptions\Model\Service\Payment $paymentService,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->tokenbaseHelper = $tokenbaseHelper;
        $this->url = $url;
        $this->vaultHelper = $vaultHelper;
        $this->addressHelper = $addressHelper;
        $this->paymentService = $paymentService;
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Payment');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Payment');
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->_coreRegistry->registry('current_subscription');

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('payment_');

        $fieldset = $form->addFieldset('fieldset_payment', ['legend' => __('Payment Information')]);

        $fieldset->addField(
            'payment_note',
            'note',
            [
                'name'  => 'payment_note',
                'label' => __(''),
                'text'  => __(
                    'This payment record will be used for future payments. <b>Any changes will take effect on the next '
                    . 'billing.</b><br />To modify payment options, please go to the '
                    . '<a href="%1" target="_blank">customer profile</a>.',
                    $this->escapeUrl(
                        $this->url->getUrl('customer/index/edit', ['id' => $subscription->getCustomerId()])
                    )
                ),
            ]
        );

        $cardOptions = [];

        try {
            $activeCard = $this->vaultHelper->getQuoteCard($quote);

            if (isset($activeCard) && $activeCard instanceof \Magento\Vault\Api\Data\PaymentTokenInterface) {
                $form->setValues([
                    'tokenbase_id' => $activeCard->getPublicHash(),
                ]);

                // Ensure selected card always displays, even if not visible.
                $cardOptions[$activeCard->getPublicHash()] = $this->vaultHelper->getCardLabel($activeCard);
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $fieldset->addField(
                'payment_error',
                'note',
                [
                    'name'  => 'payment_error',
                    'label' => __(''),
                    'text'  =>  __(
                        'This subscription has no assigned payment account. Please choose a payment account '
                        . 'below and save to prevent interruption.'
                    ),
                    'before_element_html' => '<div class="message message-error">',
                    'after_element_html' => '</div>',
                ]
            );

            $cardOptions[] = '';
        }

        /** @var \Magento\Vault\Api\Data\PaymentTokenInterface $card */
        $cards = $this->vaultHelper->getActiveCustomerCards($subscription->getCustomerId());
        foreach ($cards as $card) {
            $methodIsAvailable = $this->paymentService->isMethodAvailable(
                $card->getPaymentMethodCode(),
                $quote
            );

            if ($methodIsAvailable === true) {
                $cardOptions[$card->getPublicHash()] = $this->vaultHelper->getCardLabel($card);
            }
        }

        $fieldset->addField(
            'tokenbase_id',
            'select',
            [
                'name'     => 'tokenbase_id',
                'label'    => __('Payment Account'),
                'title'    => __('Payment Account'),
                'options'  => $cardOptions,
                'required' => true,
            ]
        );

        if (isset($activeCard) && $activeCard instanceof \Magento\Vault\Api\Data\PaymentTokenInterface) {
            $address = $this->getFormattedAddress($quote->getBillingAddress()->getDataModel());

            $fieldset->addField(
                'billing_address',
                'note',
                [
                    'name'  => 'billing_address',
                    'label' => __('Billing Address'),
                    'text'  => $address,
                    'note'  => __(
                        'Address corresponding to %1.',
                        $this->vaultHelper->getCardLabel($activeCard)
                    ),
                ]
            );
        }

        $this->setForm($form);

        $this->_eventManager->dispatch('adminhtml_subscription_view_tab_payment_prepare_form', ['form' => $form]);

        parent::_prepareForm();

        return $this;
    }

    /**
     * Get formatted card address.
     *
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     * @param string $format
     * @return string
     * @see \Magento\Customer\Model\Address\AbstractAddress::format()
     */
    public function getFormattedAddress(\Magento\Customer\Api\Data\AddressInterface $address, $format = 'html')
    {
        return $this->addressHelper->getFormattedAddress($address, $format);
    }
}
