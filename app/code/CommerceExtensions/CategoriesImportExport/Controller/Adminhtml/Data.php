<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\CategoriesImportExport\Controller\Adminhtml;

/**
 * Adminhtml Data controller
 */
abstract class Data extends \Magento\Backend\App\Action
{
	/**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed() new in 2.1
     */
    #const ADMIN_RESOURCE = 'Magento_Tax::manage_tax';
	
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('CommerceExtensions_CategoriesImportExport::import_export');
    }
}
