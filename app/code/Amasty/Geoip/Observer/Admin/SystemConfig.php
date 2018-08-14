<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Observer\Admin;

use Magento\Framework\Event\ObserverInterface;

class SystemConfig implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->messageManager = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $section = $observer->getRequest()->getParam('section');
        if ($section == 'amgeoip') {
            $this->messageManager->addWarning('When import in progress please do not close this browser window and do not attempt to operate Magento backend in separate tabs. Import usually takes from 10 to 20 minutes.');
        }
    }
}
