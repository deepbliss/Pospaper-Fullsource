<?php

namespace Mconnectsolutions\Trackorder\Plugin;

use Magento\Framework\Data\Tree\NodeFactory;
use Mconnectsolutions\Trackorder\Block\Trackorder;

class Topmenu
{

    private $nodeFactory;
    
    private $trackUrl;
    
    private $helper;

    public function __construct(
        NodeFactory $nodeFactory,
        \Mconnectsolutions\Trackorder\Helper\Data $helper,
        Trackorder $trackUrl
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->trackUrl = $trackUrl;
        $this->helper = $helper;
    }

    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject
    ) {
        $enableTrackorder = $this->helper->getConfig('mconnectsolutions_trackorder/track_order/enable');
        $blockMenu = $this->trackUrl->getTopMenu();
        if ($blockMenu && $enableTrackorder) {
            $node = $this->nodeFactory->create(
                [
                    'data' => $this->getNodeAsArray(),
                    'idField' => 'id',
                    'tree' => $subject->getMenu()->getTree()
                ]
            );
            $subject->getMenu()->addChild($node);
        }
    }

    private function getNodeAsArray()
    {
        $linkUrl = $this->trackUrl->getBaseUrl();
        return [
            'name' => __('Track Order'),
            'id' => 'track-order-menu',
            'url' => $linkUrl.'trackorder/',
            'has_active' => false,
            'is_active' => false // (expression to determine if menu item is selected or not)
        ];
    }
}
