<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Controller\Adminhtml\Memo;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    /**
     *
     */
    const ADMIN_RESOURCE = 'Magebuzz_Testimonial::save';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    public function __construct(Action\Context $context, PageFactory $resultPageFactory, Registry $registry)
    {
        $this->_coreRegistry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magebuzz_Testimonial::testimonial')
            ->addBreadcrumb(__('Testimonial'), __('Testimonial'))
            ->addBreadcrumb(__('Manage Testimonial Memo'), __('Manage Testimonial Memo'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('mb_testimonial_id');
        $model = $this->_objectManager->create('Magebuzz\Testimonial\Model\Testimonial');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This testimonial no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('testimonial_memo', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Grid') : __('New Grid'),
            $id ? __('Edit Grid') : __('New Grid')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Testimonial Edit'));

        return $resultPage;
    }
}