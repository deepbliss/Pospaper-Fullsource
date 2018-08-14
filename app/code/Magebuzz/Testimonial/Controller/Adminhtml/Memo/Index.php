<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Controller\Adminhtml\Memo;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     *
     */
    const ADMIN_RESOURCE = 'Magebuzz_Testimonial::testimonial';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Action\Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magebuzz_Testimonial::testimonial');

        // vendor_module_index1
        $resultPage->addBreadcrumb(__('Testimonials'), __('Testimonials'));
        $resultPage->addBreadcrumb(__('Manage Testimonials'), __('Manage Testimonials'));
        $resultPage->getConfig()->getTitle()->prepend(__('Testimonials'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
