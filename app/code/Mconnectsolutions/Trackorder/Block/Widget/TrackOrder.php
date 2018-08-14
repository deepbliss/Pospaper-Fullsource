<?php
namespace Mconnectsolutions\Trackorder\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class TrackOrder extends Template implements BlockInterface
{
    protected $_template = "widget/trackorder.phtml";
    
    private $helper;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mconnectsolutions\Trackorder\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    
    public function getEnableTrackOrder()
    {
        return $this->helper->getConfig('mconnectsolutions_trackorder/track_order/enable');
    }
}
