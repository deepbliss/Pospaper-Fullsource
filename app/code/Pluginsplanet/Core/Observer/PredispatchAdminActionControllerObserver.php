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
namespace Pluginsplanet\Core\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class PredispatchAdminActionControllerObserver
 * @package Pluginsplanet\Core\Observer
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{
	/**
	 * @type \Pluginsplanet\Core\Model\FeedFactory
	 */
	protected $_feedFactory;

	/**
	 * @type \Magento\Backend\Model\Auth\Session
	 */
	protected $_backendAuthSession;

	/**
	 * @param \Pluginsplanet\Core\Model\FeedFactory $feedFactory
	 * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
	 */
	public function __construct(
		\Pluginsplanet\Core\Model\FeedFactory $feedFactory,
		\Magento\Backend\Model\Auth\Session $backendAuthSession
	)
	{
		$this->_feedFactory        = $feedFactory;
		$this->_backendAuthSession = $backendAuthSession;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if ($this->_backendAuthSession->isLoggedIn()) {
			/* @var $feedModel \Pluginsplanet\Core\Model\Feed */
			$feedModel = $this->_feedFactory->create();
			$feedModel->checkUpdate();
		}
	}
}
