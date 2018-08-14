<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block;

/**
 * Class Ajax
 *
 * @package Aheadworks\RewardPoints\Block
 */
class Ajax extends \Magento\Framework\View\Element\Template
{
    /**
     * Retrieve script options encoded to json
     *
     * @return string
     */
    public function getScriptOptions()
    {
        $params = [
            'url' => $this->getUrl(
                'aw_rewardpoints/block/render/',
                [
                    '_current' => true,
                    '_secure' => $this->templateContext->getRequest()->isSecure()
                ]
            ),
            'originalRequest' => [
                'route' => $this->getRequest()->getRouteName(),
                'controller' => $this->getRequest()->getControllerName(),
                'action' => $this->getRequest()->getActionName(),
                'uri' => $this->getRequest()->getRequestUri(),
            ]
        ];
        return json_encode($params);
    }
}
