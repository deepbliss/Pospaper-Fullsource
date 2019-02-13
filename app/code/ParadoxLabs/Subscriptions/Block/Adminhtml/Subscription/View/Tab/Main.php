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
 * Main tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusModel;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodModel;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * Main constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusModel
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodModel
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusModel,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodModel,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->statusModel = $statusModel;
        $this->periodModel = $periodModel;
        $this->customerRepository = $customerRepository;
        $this->currencyFactory = $currencyFactory;
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
        return __('Details');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Details');
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

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('subscription_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Subscription Details')]);

        if ($subscription->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $subscription->getQuote();
        $products = '';

        try {
            /** @var \Magento\Quote\Model\Quote\Item $item */
            foreach ($quote->getAllItems() as $item) {
                $products .= __(
                    '%3 x %1 (SKU: %2)<br />',
                    $item->getName(),
                    $item->getSku(),
                    $item->getQty()
                );
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $products .= '<div class="message message-error">'
                . __('Product(s) could not be loaded: %1', $e->getMessage())
                . '</div>';
        }

        $fieldset->addField(
            'product_label',
            'note',
            [
                'name'  => 'product',
                'label' => __('Product(s)'),
                'text'  => $products,
            ]
        );

        $fieldset->addField(
            'description',
            'text',
            [
                'name'  => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
            ]
        );

        $this->addSubtotalField($subscription, $fieldset);

        $fieldset->addField(
            'status_label',
            'note',
            [
                'name'  => 'status',
                'label' => __('Status'),
                'text'  => $this->statusModel->getOptionText($subscription->getStatus()),
            ]
        );

        $fieldset->addField(
            'created_at_formatted',
            'note',
            [
                'name'  => 'created_at',
                'label' => __('Started'),
                'text'  => $this->_localeDate->formatDateTime(
                    $subscription->getCreatedAt(),
                    \IntlDateFormatter::MEDIUM
                )
            ]
        );

        $fieldset->addField(
            'last_run_formatted',
            'note',
            [
                'name'  => 'last_run',
                'label' => __('Last run'),
                'text'  => $this->_localeDate->formatDateTime(
                    $subscription->getLastRun(),
                    \IntlDateFormatter::MEDIUM
                )
            ]
        );

        $subscription->setData(
            'next_run_formatted',
            $this->_localeDate->date(
                $subscription->getNextRun(),
                \IntlDateFormatter::MEDIUM
            )
        );

        $fieldset->addField(
            'next_run_formatted',
            'date',
            [
                'name'        => 'next_run',
                'label'       => __('Next run'),
                'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::MEDIUM),
                'time_format' => $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT),
                'style'       => 'width:200px',
            ]
        );

        $fieldset->addField(
            'run_count',
            'label',
            [
                'name'  => 'run_count',
                'label' => __('Number of times billed'),
            ]
        );

        $fieldset->addField(
            'frequency_count',
            'text',
            [
                'name'  => 'frequency_count',
                'label' => __('Frequency: Every'),
                'title' => __('Frequency: Every'),
            ]
        );

        $fieldset->addField(
            'frequency_unit',
            'select',
            [
                'name'    => 'frequency_unit',
                'title'   => __('Frequency Unit'),
                'options' => $this->periodModel->getOptionArrayPlural(),
            ]
        );

        $fieldset->addField(
            'length',
            'text',
            [
                'name'  => 'length',
                'label' => __('Length'),
                'title' => __('Length'),
                'note'  => __('Number of cycles the subscription should run. 0 for indefinite.'),
            ]
        );

        if ($subscription->getCustomerId() > 0) {
            try {
                $customer = $this->customerRepository->getById($subscription->getCustomerId());

                $fieldset->addField(
                    'customer',
                    'note',
                    [
                        'name'  => 'customer',
                        'label' => __('Customer'),
                        'text'  => __(
                            '<a href="%1">%2 %3</a> (%4)',
                            $this->escapeUrl(
                                $this->getUrl('customer/index/edit', ['id' => $subscription->getCustomerId()])
                            ),
                            $customer->getFirstname(),
                            $customer->getLastname(),
                            $customer->getEmail()
                        )
                    ]
                );
            } catch (\Exception $e) {
                // Do nothing on exception.
            }
        }

        $this->addStoreField($subscription, $fieldset);

        $fieldset->addField(
            'notes',
            'textarea',
            [
                'name'  => 'notes',
                'label' => __('Notes'),
                'value' => $subscription->getAdditionalInformation('notes'),
            ]
        );

        $form->setValues($subscription->getData() + $subscription->getAdditionalInformation());
        $this->setForm($form);

        $this->_eventManager->dispatch('adminhtml_subscription_view_tab_main_prepare_form', ['form' => $form]);

        parent::_prepareForm();

        return $this;
    }

    /**
     * Add store name field to the fieldset.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    protected function addStoreField(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset
    ) {
        if ($this->_storeManager->isSingleStoreMode() === false) {
            try {
                $store = $this->_storeManager->getStore($subscription->getStoreId());

                $fieldset->addField(
                    'store_id',
                    'note',
                    [
                        'name' => 'store_id',
                        'label' => __('Purchase Point'),
                        'text' => $store->getName(),
                    ]
                );
            } catch (\Exception $e) {
                // Do nothing on exception.
            }
        }
    }

    /**
     * Add subtotal field to the fieldset.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @return void
     */
    protected function addSubtotalField(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset
    ) {
        try {
            /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $subscription->getQuote();
            $currency = $quote->getQuoteCurrencyCode();

            $currencyModel = $this->currencyFactory->create();
            $currencyModel->load($currency);

            $fieldset->addField(
                'subtotal',
                'note',
                [
                    'name' => 'subtotal',
                    'label' => __('Subtotal'),
                    'text' => $currencyModel->formatTxt($subscription->getSubtotal()),
                    'note' => __('Note: Subtotals do not include shipping, tax, or other possible surcharges. '
                                . 'Actual order totals may vary over time.'),
                ]
            );
        } catch (\Exception $e) {
            // Do nothing on exception.
        }
    }
}
