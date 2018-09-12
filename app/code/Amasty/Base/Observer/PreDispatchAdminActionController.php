<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Observer;

use Magento\Framework\Event\ObserverInterface;

class PreDispatchAdminActionController implements ObserverInterface
{
    /**
     * @var \Amasty\Base\Model\FeedFactory
     */
    private $feedFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    private $backendSession;

    public function __construct(
        \Amasty\Base\Model\FeedFactory $feedFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession
    ) {
        $this->feedFactory = $feedFactory;
        $this->backendSession = $backendAuthSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->backendSession->isLoggedIn()) {
            /** @var \Amasty\Base\Model\Feed $feedModel */
            $feedModel = $this->feedFactory->create();
            $feedModel->checkUpdate();
            $feedModel->removeExpiredItems();
        }
    }
}
