<?php
namespace Emipro\Importexportattributeoption\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Index extends \Magento\Backend\App\Action
{
    private $helper;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Emipro\Importexportattributeoption\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->helper = $helper;
    }

    public function execute()
    {
        $this->helper->validateImportExportData();
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
