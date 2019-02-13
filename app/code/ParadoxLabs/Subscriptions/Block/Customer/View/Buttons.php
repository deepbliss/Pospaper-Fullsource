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

namespace ParadoxLabs\Subscriptions\Block\Customer\View;

use Magento\Framework\View\Element\Template;
use ParadoxLabs\Subscriptions\Model\Source\Status as StatusSource;

/**
 * Buttons Class
 */
class Buttons extends \ParadoxLabs\Subscriptions\Block\Customer\View
{
    /**
     * @var StatusSource
     */
    protected $statusSource;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Buttons constructor.
     *
     * @param Template\Context $context
     * @param Context $viewContext
     * @param StatusSource $statusSource
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \ParadoxLabs\Subscriptions\Block\Customer\View\Context $viewContext,
        StatusSource $statusSource,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        array $data
    ) {
        parent::__construct(
            $context,
            $viewContext,
            $data
        );

        $this->statusSource = $statusSource;
        $this->formKey = $formKey;
        $this->config = $config;
    }

    /**
     * Get status source model.
     *
     * @return StatusSource
     */
    public function getStatusSource()
    {
        return $this->statusSource;
    }

    /**
     * Check whether the given subscription can be paused.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     */
    public function canPause(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        if ($this->config->allowCustomerToPause($subscription->getStoreId()) !== true) {
            return false;
        }

        return $this->statusSource->canSetStatus($subscription, StatusSource::STATUS_PAUSED);
    }

    /**
     * Get URL to pause the current subscription.
     *
     * @return string
     */
    public function getPauseUrl()
    {
        return $this->getUrl(
            '*/*/changeStatus',
            [
                'id'       => $this->getSubscription()->getId(),
                'status'   => StatusSource::STATUS_PAUSED,
                'form_key' => $this->formKey->getFormKey(),
            ]
        );
    }

    /**
     * Check whether the given subscription can be canceled.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     */
    public function canCancel(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        if ($this->config->allowCustomerToCancel($subscription->getStoreId()) !== true) {
            return false;
        }

        return $this->statusSource->canSetStatus($subscription, StatusSource::STATUS_CANCELED);
    }

    /**
     * Get URL to cancel the current subscription.
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getUrl(
            '*/*/changeStatus',
            [
                'id'       => $this->getSubscription()->getId(),
                'status'   => StatusSource::STATUS_CANCELED,
                'form_key' => $this->formKey->getFormKey(),
            ]
        );
    }

    /**
     * Check whether the given subscription can be activated.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return bool
     */
    public function canActivate(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        return $this->statusSource->canSetStatus($subscription, StatusSource::STATUS_ACTIVE);
    }

    /**
     * Get URL to reactivate the current subscription.
     *
     * @return string
     */
    public function getActivateUrl()
    {
        return $this->getUrl(
            '*/*/changeStatus',
            [
                'id'       => $this->getSubscription()->getId(),
                'status'   => StatusSource::STATUS_ACTIVE,
                'form_key' => $this->formKey->getFormKey(),
            ]
        );
    }
}
