<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Block\Adminhtml\Rule\Edit\Tab\Test\Renderer;

/**
 * Adminhtml customers wishlist grid item renderer for name/options cell
 */
class Run extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Amasty\Acart\Model\ConfigProvider
     */
    private $configProvider;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Amasty\Acart\Model\ConfigProvider $configProvider,
        array $data = []
    ) {
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $item)
    {
        $recipientEmail = $this->configProvider->getRecipientEmailForTest();

        return '<button type="button" class="scalable task" onclick="amastyAcartTest.send(' . $item->getId()
            . ')"><span><span><span>' . __('Send') . '</span></span></span></button><br/><small><i>to '
            . $recipientEmail . '</i></small>';
    }
}
