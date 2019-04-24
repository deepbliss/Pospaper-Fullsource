<?php

namespace Experius\PageNotFound\Controller\Adminhtml;

use Experius\PageNotFound\Model\ResourceModel\PageNotFound\CollectionFactory;

abstract class Pagenotfound extends \Magento\Backend\App\Action
{

    protected $_coreRegistry;
    protected $_collectionFactory;
    protected $_massActionFilter;
    protected $resultPageFactory;
    protected $resultForwardFactory;
    const ADMIN_RESOURCE = 'Experius_PageNotFound::top_level';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        CollectionFactory $collectionFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Ui\Component\MassAction\Filter $massActionFilter
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        $this->_massActionFilter = $massActionFilter;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Experius'), __('Experius'))
            ->addBreadcrumb(__('Page Not Found'), __('Page Not Found'));
        return $resultPage;
    }

    protected function _createMainCollection()
    {
        $collection = $this->_collectionFactory->create();
        return $collection;
    }
}
