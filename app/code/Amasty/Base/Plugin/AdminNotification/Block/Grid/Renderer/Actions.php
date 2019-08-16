<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Plugin\AdminNotification\Block\Grid\Renderer;

use Magento\AdminNotification\Block\Grid\Renderer\Actions as NativeActions;

/**
 * Class Actions
 * @package Amasty\Base\Plugin\AdminNotification\Block\Grid\Renderer
 */
class Actions
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param NativeActions $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function aroundRender(
        NativeActions $subject,
        \Closure $proceed,
        \Magento\Framework\DataObject $row
    ) {
        $result = $proceed($row);
        if ($row->getData('is_amasty')) {
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl('ambase/notification/frequency', ['action' => 'less']),
                __('Show less of these messages')
            );
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl('ambase/notification/frequency', ['action' => 'more']),
                __('Show more of these messages')
            );
            $result .= sprintf(
                '<a class="action" href="%s" title="%2$s">%2$s</a>',
                $this->urlBuilder->getUrl(
                    'adminhtml/system_config/edit',
                    ['section' => 'amasty_base', '_fragment' => 'amasty_base_notifications-head']
                ),
                __('Unsubscribe')
            );
        }

        return  $result;
    }
}
