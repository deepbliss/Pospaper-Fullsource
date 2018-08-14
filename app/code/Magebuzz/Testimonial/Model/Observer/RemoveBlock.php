<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Model\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RemoveBlock implements ObserverInterface
{
    protected $_scopeConfig;

    /**
     * @var \Magebuzz\Testimonial\Helper\Data
     */
    protected $_helper;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magebuzz\Testimonial\Helper\Data $helper
    ) {
        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getLayout();
        $block = $layout->getBlock('testimonialus.link');

        /*
         * Check value top link in store config
         */
        if ($block) {
            if ($this->_helper->isEnabledInTopLink() == 0) {
                $layout->unsetElement('testimonialus.link');
            }
        }

        /*
         * Check value side bar in store config
         */
        $sidebar = $layout->getBlock('magekyo.block.sidebar');
        if($sidebar) {
            if($this->_helper->isEnabledInSidebar() == 0) {
                $layout->unsetElement('magekyo.block.sidebar');
            }
        }
    }
}