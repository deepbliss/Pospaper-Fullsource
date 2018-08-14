<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Customers;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Index
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_RewardPoints::aw_reward_points_customers';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage **/
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Aheadworks_RewardPoints::aw_reward_points_customers');
        $resultPage->addBreadcrumb(__('Aheadworks Reward Points'), __('Customers'));
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));

        return $resultPage;
    }
}
