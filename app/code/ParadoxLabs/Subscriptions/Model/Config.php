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

namespace ParadoxLabs\Subscriptions\Model;

/**
 * Subscriptions configuration manager
 *
 * A big jumble of config value loading, with some value manipulation and typecasing thrown in.
 *
 * @api
 */
class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check whether subscriptions module is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function moduleIsActive($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Check whether scheduled billing is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function scheduledBillingIsEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/billing_active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get default label for the subscription custom option.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getSubscriptionLabel($storeId = null)
    {
        return (string)__(
            $this->scopeConfig->getValue(
                'subscriptions/general/option_label',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * Get default label for subscription installments.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getInstallmentLabel($storeId = null)
    {
        return (string)__(
            $this->scopeConfig->getValue(
                'subscriptions/general/installment_label',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * Check whether custom option should be skipped if only a single option is available for a product.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function skipSingleOption($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/always_add_custom_option',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? false : true;
    }

    /**
     * Check whether to group same-day subscriptions for a customer.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function groupSameDaySubscriptions($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/group_same_day',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get input type for the subscription custom option.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getInputType($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/input_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: 'drop_down';
    }

    /**
     * Get shipping method to force new subscriptions to (if any).
     *
     * Must be the complete code as stored on quote_address.shipping_method, such as flatrate_flatrate or ups_08.
     * NB: The given method MUST BE AVAILABLE for the quote. If it can't be chosen on checkout, it won't go through.
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function forceShippingMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/force_shipping_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: null;
    }

    /**
     * Check whether shipping fallback is enabled.
     *
     * If yes, subscriptions will switch to cheapest alternative if chosen is unavailable.
     * If no, rebilling will fail with "Please specify a shipping method." and subscription will be put on hold.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isShippingFallbackEnabled($storeId = null)
    {
        return (bool)$this->scopeConfig->getValue(
            'subscriptions/general/enable_shipping_fallback',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get whether customer is allowed to cancel their own subscriptions.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function allowCustomerToCancel($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/allow_customer_cancel',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get whether customer is allowed to pause their own subscriptions.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function allowCustomerToPause($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/general/allow_customer_pause',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get days of week allowed for scheduling.
     *
     * NB: Careful, Magento weekdays are zero-indexed.
     *
     * @param int|null $storeId
     * @return int[]
     */
    public function getSchedulingDaysOfWeekAllowed($storeId = null)
    {
        $daysOfWeek = $this->scopeConfig->getValue(
            'subscriptions/schedule/days_of_week',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $daysOfWeek = explode(',', $daysOfWeek);
        $daysOfWeek = array_map('intval', $daysOfWeek);

        // Default to all-allowed if nothing selected.
        if (count($daysOfWeek) === 0) {
            $daysOfWeek = [0, 1, 2, 3, 4, 5, 6];
        }

        return $daysOfWeek;
    }

    /**
     * Get days of month allowed for scheduling.
     *
     * Does not account for varying month lengths--just raw by the numbers.
     *
     * @param int|null $storeId
     * @return int[]
     */
    public function getSchedulingDaysOfMonthAllowed($storeId = null)
    {
        $daysOfMonth = $this->scopeConfig->getValue(
            'subscriptions/schedule/days_of_month',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $daysOfMonth = explode(',', $daysOfMonth);
        $daysOfMonth = array_map('intval', $daysOfMonth);

        // Default to all-allowed if nothing selected.
        if (count($daysOfMonth) === 0) {
            $daysOfMonth = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
        }

        return $daysOfMonth;
    }

    /**
     * Get months allowed for scheduling.
     *
     * @param int|null $storeId
     * @return int[]
     */
    public function getSchedulingMonthsAllowed($storeId = null)
    {
        $months = $this->scopeConfig->getValue(
            'subscriptions/schedule/months',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $months = explode(',', $months);
        $months = array_map('intval', $months);

        // Default to all-allowed if nothing selected.
        if (count($months) === 0) {
            $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        }

        return $months;
    }

    /**
     * Get scheduling blackout dates as Y-m-d, if any.
     *
     * @param int|null $storeId
     * @return string[]
     */
    public function getSchedulingBlackoutDates($storeId = null)
    {
        $blackoutDates = $this->scopeConfig->getValue(
            'subscriptions/schedule/blackout_dates',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (!empty($blackoutDates)) {
            $blackoutDates = explode("\n", $blackoutDates);

            foreach ($blackoutDates as $k => $v) {
                $v = strtotime($v);

                if ($v !== false) {
                    $blackoutDates[$k] = date('Y-m-d', $v);
                } else {
                    unset($blackoutDates[$k]);
                }
            }

            return $blackoutDates;
        }

        return [];
    }

    /**
     * Get whether the given payment method is vault-enabled.
     *
     * @param string $methodCode
     * @param int|null $storeId
     * @return bool
     */
    public function vaultMethodIsActive($methodCode, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            'payment/' . $methodCode . '_cc_vault/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get the given payment method's group.
     *
     * @param string $methodCode
     * @param int|null $storeId
     * @return string|null
     */
    public function getPaymentMethodGroup($methodCode, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            'payment/' . $methodCode . '/group',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ?: null;
    }

    /**
     * Check whether upcoming-billing notice emails are enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function billingNoticesAreEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_notice/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get advance notice threshold for billing notices (in days).
     *
     * @param int|null $storeId
     * @return int
     */
    public function getBillingNoticeAdvance($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(
            'subscriptions/billing_notice/advance_period',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get email template for billing notices.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingNoticeTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_notice/template',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy method (CC, BCC) for billing notices.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingNoticeCopyMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_notice/copy_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy-to emails for billing notices.
     *
     * @param int|null $storeId
     * @return string[]
     */
    public function getBillingNoticeCopyEmails($storeId = null)
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(
                'subscriptions/billing_notice/copy_to',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * Get sender identity for billing notices.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingNoticeSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_notice/identity',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check whether billing failure emails to the admin are enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function billingFailedEmailsEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_failed/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get email template for billing failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingFailedTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_failed/template',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy method (CC, BCC) for billing failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingFailedCopyMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_failed/copy_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy-to emails for billing failure emails.
     *
     * @param int|null $storeId
     * @return string[]
     */
    public function getBillingFailedCopyEmails($storeId = null)
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(
                'subscriptions/billing_failed/copy_to',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * Get sender identity for billing failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getBillingFailedSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/billing_failed/identity',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get recipient contact for billing failure emails.
     *
     * @param int|null $storeId
     * @return array
     */
    public function getBillingFailedRecipient($storeId = null)
    {
        $identity = $this->scopeConfig->getValue(
            'subscriptions/billing_failed/receiver',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return [
            'email' => $this->scopeConfig->getValue(
                'trans_email/ident_' . $identity . '/email',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            ),
            'name'  => $this->scopeConfig->getValue(
                'trans_email/ident_' . $identity . '/name',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            ),
        ];
    }

    /**
     * Check whether payment failure emails to the customer are enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function paymentFailedEmailsEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/payment_failed/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        ) ? true : false;
    }

    /**
     * Get email template for payment failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentFailedTemplate($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/payment_failed/template',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy method (CC, BCC) for payment failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentFailedCopyMethod($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/payment_failed/copy_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get copy-to emails for payment failure emails.
     *
     * @param int|null $storeId
     * @return string[]
     */
    public function getPaymentFailedCopyEmails($storeId = null)
    {
        return explode(
            ',',
            $this->scopeConfig->getValue(
                'subscriptions/payment_failed/copy_to',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            )
        );
    }

    /**
     * Get sender identity for payment failure emails.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPaymentFailedSender($storeId = null)
    {
        return $this->scopeConfig->getValue(
            'subscriptions/payment_failed/identity',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
