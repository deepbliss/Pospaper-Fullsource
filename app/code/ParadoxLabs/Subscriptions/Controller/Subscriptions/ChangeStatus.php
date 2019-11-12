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

namespace ParadoxLabs\Subscriptions\Controller\Subscriptions;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use ParadoxLabs\Subscriptions\Model\Source\Status;

/**
 * ChangeStatus Class
 */
class ChangeStatus extends View
{
    /**
     * @var Status
     */
    protected $statusSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * ChangeStatus constructor.
     *
     * @param Context $context
     * @param Session $customerSession *Proxy
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\TokenBase\Model\CardFactory $cardFactory
     * @param \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository
     * @param \ParadoxLabs\TokenBase\Helper\Data $helper
     * @param \ParadoxLabs\TokenBase\Helper\Address $addressHelper
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer *Proxy
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param Status $statusSource
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $resultPageFactory,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\TokenBase\Model\CardFactory $cardFactory,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository,
        \ParadoxLabs\TokenBase\Helper\Data $helper,
        \ParadoxLabs\TokenBase\Helper\Address $addressHelper,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        Status $statusSource
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $resultPageFactory,
            $formKeyValidator,
            $registry,
            $cardFactory,
            $cardRepository,
            $helper,
            $addressHelper,
            $subscriptionRepository,
            $currentCustomer
        );

        $this->statusSource = $statusSource;
        $this->config = $config;
    }

    /**
     * Subscription status-change action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $initialized    = $this->initModels();
        $resultRedirect = $this->resultRedirectFactory->create();

        /**
         * If we were not able to load the model, short-circuit.
         */
        if ($initialized !== true || $this->formKeyIsValid() !== true) {
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->registry->registry('current_subscription');

        try {
            $newStatus = $this->getAndValidateStatus();

            $subscription->setStatus($newStatus);
            $this->subscriptionRepository->save($subscription);

            $this->messageManager->addSuccessMessage(
                __(
                    'Subscription status changed to "%1".',
                    $this->statusSource->getOptionText($subscription->getStatus())
                )
            );

            $resultRedirect->setPath('*/*/view', ['entity_id' => $subscription->getId(), '_current' => true]);
        } catch (\Exception $e) {
            $this->helper->log('subscriptions', (string)$e);
            $this->messageManager->addErrorMessage(__('ERROR: %1', $e->getMessage()));

            $resultRedirect->setPath('*/*/view', ['entity_id' => $subscription->getId(), '_current' => true]);
        }

        return $resultRedirect;
    }

    /**
     * Get the new status to be set, and make sure we actually have permission to do so.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getAndValidateStatus()
    {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->registry->registry('current_subscription');

        $newStatus = $this->getRequest()->getParam('status');
        $okay      = false;

        /**
         * Check whether we are allowed to make this change.
         */
        if ($this->statusSource->canSetStatusAsCustomer($subscription, $newStatus) !== true) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid status request.'));
        }

        return $newStatus;
    }
}
