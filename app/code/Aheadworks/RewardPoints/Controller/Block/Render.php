<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Block;

use Aheadworks\RewardPoints\Block\Product\View\Discount;
use Aheadworks\RewardPoints\Block\Product\View\Share;
use Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Toplink;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\App\Action\Context;

/**
 * Class Render
 *
 * @package Aheadworks\RewardPoints\Controller\Block
 */
class Render extends \Magento\Framework\App\Action\Action
{
    /**
     * @var InlineInterface
     */
    private $translateInline;

    /**
     * @param Context $context
     * @param InlineInterface $translateInline
     */
    public function __construct(
        Context $context,
        InlineInterface $translateInline
    ) {
        parent::__construct($context);
        $this->translateInline = $translateInline;
    }

    /**
     * Returns block content depends on ajax request
     *
     * @return \Magento\Framework\Controller\Result\Redirect|void
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }
        $currentRoute = $this->getRequest()->getRouteName();
        $currentControllerName = $this->getRequest()->getControllerName();
        $currentActionName = $this->getRequest()->getActionName();
        $currentRequestUri = $this->getRequest()->getRequestUri();

        $origRequest = $this->getRequest()->getParam('originalRequest');
        if ($origRequest && is_string($origRequest)) {
            $origRequest = json_decode($origRequest, true);
        }
        $this->getRequest()->setRouteName($origRequest['route']);
        $this->getRequest()->setControllerName($origRequest['controller']);
        $this->getRequest()->setActionName($origRequest['action']);
        $this->getRequest()->setRequestUri($origRequest['uri']);

        $blocks = $this->getRequest()->getParam('blocks');
        $data = $this->getBlocks($blocks);

        $this->getRequest()->setRouteName($currentRoute);
        $this->getRequest()->setControllerName($currentControllerName);
        $this->getRequest()->setActionName($currentActionName);
        $this->getRequest()->setRequestUri($currentRequestUri);

        $this->translateInline->processResponseBody($data);
        $this->getResponse()->appendBody(json_encode($data));
    }

    /**
     * Get blocks from layout
     *
     * @param string $blocks
     * @return string[]
     */
    private function getBlocks($blocks)
    {
        if (!$blocks) {
            return [];
        }
        $blocks = json_decode($blocks);

        $data = [];
        $layout = $this->_view->getLayout();
        foreach ($blocks as $key => $blockName) {
            $class = '';
            switch ($blockName) {
                case 'aw_reward_points.product.view.discount':
                    $class = Discount::class;
                    break;
                case 'aw_rp_balance_toplink':
                    $class = Toplink::class;
                    break;
                case 'aw_reward_points.product.view.share':
                    $class = Share::class;
                    break;
            }
            if ($class) {
                $blockInstance = $layout->createBlock($class);
                if (is_object($blockInstance)) {
                    $blockInstance->setNameInLayout($blockName . '_' . $key);
                    $data[$blockName] = $blockInstance->toHtml();
                }
            }
        }
        return $data;
    }
}
