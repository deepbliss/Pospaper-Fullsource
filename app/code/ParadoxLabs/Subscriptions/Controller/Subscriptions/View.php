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

/**
 * View: Show subscription info/form.
 */
class View extends \ParadoxLabs\TokenBase\Controller\Paymentinfo
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * View constructor.
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
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
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
            $addressHelper
        );

        $this->subscriptionRepository = $subscriptionRepository;
        $this->currentCustomer = $currentCustomer;
    }

    /**
     * Subscriptions view page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $initialized = $this->initModels();

        if ($initialized !== true) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        $subscription = $this->registry->registry('current_subscription');

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Subscriptions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Subscription # %1', $subscription->getIncrementId()));

        /** @var \Magento\Theme\Block\Html\Title $titleBlock */
        $titleBlock = $resultPage->getLayout()->getBlock('page.main.title');
        if ($titleBlock) {
            $titleBlock->setPageTitle(
                __('Subscription # %1', $subscription->getIncrementId())
            );
        }

        return $resultPage;
    }

    /**
     * Initialize subscription model for the current request.
     *
     * @return bool Successful or not
     */
    protected function initModels()
    {
        /**
         * Load subscription by ID.
         */
        $id = (int)$this->getRequest()->getParam('id');

        try {
            /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
            $subscription = $this->subscriptionRepository->getById($id);
        } catch (\Exception $e) {
            return false;
        }

        $customerId = $this->currentCustomer->getCustomerId();

        /**
         * If it doesn't exist, or isn't ours, fail (redirect to grid).
         */
        if ($id < 1 || $subscription->getId() != $id || $subscription->getCustomerId() != $customerId) {
            return false;
        }

        $this->registry->register('current_subscription', $subscription);

        return true;
    }
}
