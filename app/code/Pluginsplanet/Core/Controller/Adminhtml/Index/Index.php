<?php
/**
 * Pluginsplanet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the pluginsplanet.com license that is
 * available through the world-wide-web at this URL:
 * https://www.pluginsplanet.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Pluginsplanet
 * @package     Pluginsplanet_Core
 * @copyright   Copyright (c) 2016 Pluginsplanet (http://www.pluginsplanet.com/)
 * @license     https://www.pluginsplanet.com/LICENSE.txt
 */
namespace Pluginsplanet\Core\Controller\Adminhtml\Index;

/**
 * Class Index
 * @package Pluginsplanet\Core\Controller\Adminhtml\Index
 */
class Index extends \Magento\Backend\App\Action
{
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		$this->resultPageFactory = $resultPageFactory;

		parent::__construct($context);
	}

	/**
	 * @return \Magento\Backend\Model\View\Result\Page
	 */
	public function execute()
	{
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Pluginsplanet_Core::partners');
		$resultPage->addBreadcrumb(__('Partners'), __('Partners'));
		$resultPage->getConfig()->getTitle()->prepend(__('PluginsPlanet.com Marketplace'));

		return $resultPage;
	}

	/**
	 * Check for is allowed
	 *
	 * @return boolean
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Pluginsplanet_Core::partners');
	}
}
