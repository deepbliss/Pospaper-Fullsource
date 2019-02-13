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

namespace ParadoxLabs\Subscriptions\Block\Adminhtml\Customer;

use Magento\Backend\Block\Template\Context;
use Magento\Ui\Component\Layout\Tabs\TabWrapper;

/**
 * Class Tab
 */
class Tab extends TabWrapper
{
    /**
     * @var bool
     */
    protected $isAjaxLoaded = true;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\Subscriptions\Model\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->authorization = $context->getAuthorization();
        $this->registry = $registry;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function canShowTab()
    {
        if ($this->config->moduleIsActive() === true
            && $this->authorization->isAllowed('ParadoxLabs_Subscriptions::subscriptions')) {
            // Show tab only if existing customer. ID is stored on the registry on edit.
            return $this->registry->registry(\Magento\Customer\Controller\RegistryConstants::CURRENT_CUSTOMER_ID);
        }

        return false;
    }

    /**
     * Return Tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Subscriptions');
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('subscriptions/customer/subscriptionsGrid', ['_current' => true]);
    }
}
