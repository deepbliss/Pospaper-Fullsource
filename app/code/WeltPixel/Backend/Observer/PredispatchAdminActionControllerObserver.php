<?php
namespace WeltPixel\Backend\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * PredispatchAdminActionControllerObserver observer
 *
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{
    /**
     * @var \WeltPixel\Backend\Model\FeedFactory
     */
    protected $_feedFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_backendAuthSession;

    /**
     * @param \WeltPixel\Backend\Model\FeedFactory $feedFactory
     * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
     */
    public function __construct(
        \WeltPixel\Backend\Model\FeedFactory $feedFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession
    ) {
        $this->_feedFactory = $feedFactory;
        $this->_backendAuthSession = $backendAuthSession;
    }

    /**
     * Predispath admin action controller
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_backendAuthSession->isLoggedIn()) {
            $feedModel = $this->_feedFactory->create();
            /* @var $feedModel \WeltPixel\Backend\Model\Feed */
            $feedModel->checkUpdate();
        }
    }
}
