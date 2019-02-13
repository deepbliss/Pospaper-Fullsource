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

/**
 * Edit Class
 */
class Edit extends View
{
    /**
     * Subscriptions edit page
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
        $resultPage->getConfig()->getTitle()->prepend(__('Edit'));

        /** @var \Magento\Theme\Block\Html\Title $titleBlock */
        $titleBlock = $resultPage->getLayout()->getBlock('page.main.title');
        if ($titleBlock) {
            $titleBlock->setPageTitle(
                __('Subscription # %1', $subscription->getIncrementId())
            );
        }

        return $resultPage;
    }
}
