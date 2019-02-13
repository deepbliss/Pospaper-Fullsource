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

namespace ParadoxLabs\Subscriptions\Block\Customer\View;

use Magento\Framework\View\Element\Template;

/**
 * Status Class
 */
class Status extends \ParadoxLabs\Subscriptions\Block\Customer\View
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusModel;

    /**
     * Status constructor.
     *
     * @param Template\Context $context
     * @param Context $viewContext
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \ParadoxLabs\Subscriptions\Block\Customer\View\Context $viewContext,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusModel,
        array $data
    ) {
        parent::__construct(
            $context,
            $viewContext,
            $data
        );

        $this->statusModel = $statusModel;
    }

    /**
     * Get status subsription.
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return $this->statusModel->getOptionText(
            $this->getSubscription()->getStatus()
        );
    }
}
