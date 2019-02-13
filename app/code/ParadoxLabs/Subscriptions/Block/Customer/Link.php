<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Block\Customer;

/**
 * Add 'subscription' link to the customer account.
 */
class Link extends \Magento\Framework\View\Element\Html\Link\Current
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Link constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        array $data
    ) {
        parent::__construct($context, $defaultPath, $data);

        $this->config = $config;
    }

    /**
     * Get href URL - force secure
     *
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl($this->getPath(), ['_secure' => true]);
    }

    /**
     * Render block HTML - only if enabled.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->config->moduleIsActive() === true) {
            return parent::_toHtml();
        }

        return '';
    }
}
