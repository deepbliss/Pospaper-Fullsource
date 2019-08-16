<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Observer\Admin;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class SystemConfig
 */
class SystemConfig implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * SystemConfig constructor.
     *
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $section = $observer->getRequest()->getParam('section');
        if ($section == 'amgeoip') {
            $this->messageManager->addWarning(
                __('When import in progress please do not close this '
                . 'browser window and do not attempt to operate Magento backend in separate tabs. '
                . 'Import usually takes from 10 to 20 minutes.')
            );
        }
    }
}
