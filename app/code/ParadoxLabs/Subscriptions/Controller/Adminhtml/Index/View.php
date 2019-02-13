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

namespace ParadoxLabs\Subscriptions\Controller\Adminhtml\Index;

/**
 * Subscriptions form
 */
class View extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->customerRepository = $customerRepository;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->helper = $helper;

        parent::__construct($context);
    }

    /**
     * Subscriptions list action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $initialized = $this->initModels();

        if ($initialized !== true) {
            $this->messageManager->addErrorMessage(__('Could not load the requested subscription.'));

            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/index');
            return $resultRedirect;
        }

        $resultPage = $this->resultPageFactory->create();

        /**
         * Set active menu item
         */
        $resultPage->setActiveMenu('ParadoxLabs_Subscriptions::subscriptions_manage');
        $resultPage->getConfig()->getTitle()->prepend(
            __(
                'Subscription # %1',
                $this->registry->registry('current_subscription')->getIncrementId()
            )
        );

        /**
         * Add breadcrumb item
         */
        $resultPage->addBreadcrumb(__('Subscriptions'), __('Subscriptions'));
        $resultPage->addBreadcrumb(__('Manage Subscriptions'), __('Manage Subscriptions'));

        return $resultPage;
    }

    /**
     * Determine if authorized to perform these actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ParadoxLabs_Subscriptions::subscriptions');
    }

    /**
     * Initialize subscription/customer models for the current request.
     *
     * @return bool Successful or not
     */
    protected function initModels()
    {
        /**
         * Load subscription by ID.
         */
        $id = (int)$this->getRequest()->getParam('entity_id');

        try {
            /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
            $subscription = $this->subscriptionRepository->getById($id);
        } catch (\Exception $e) {
            return false;
        }

        /**
         * If it doesn't exist, fail (redirect to grid).
         */
        if ($id < 1 || $subscription->getId() != $id) {
            return false;
        }

        $this->registry->register('current_subscription', $subscription);

        /**
         * Load and set customer (if any) for TokenBase.
         */
        if ($subscription->getCustomerId() > 0) {
            try {
                $customer = $this->customerRepository->getById($subscription->getCustomerId());

                if ($customer->getId() == $subscription->getCustomerId()) {
                    $this->registry->register('current_customer', $customer);
                }
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                // Throw a warning, but proceed even if customer doesn't exist.
                $this->messageManager->addErrorMessage(__('Could not load the subscription customer (ID %1).', $subscription->getCustomerId()));
            }
        }

        return true;
    }
}
