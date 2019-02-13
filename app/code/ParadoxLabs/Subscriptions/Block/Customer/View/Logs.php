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

/**
 * History Class
 */
class Logs extends \ParadoxLabs\Subscriptions\Block\Customer\View
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection
     */
    protected $logCollection;

    /**
     * History constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Context $viewContext
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \ParadoxLabs\Subscriptions\Block\Customer\View\Context $viewContext,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory,
        array $data
    ) {
        parent::__construct(
            $context,
            $viewContext,
            $data
        );

        $this->logCollectionFactory = $logCollectionFactory;
    }

    /**
     * Get log collection for the current subscription.
     *
     * @return \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection
     */
    public function getCollection()
    {
        if ($this->logCollection === null) {
            $this->logCollection = $this->logCollectionFactory->create();
            $this->logCollection->addSubscriptionFilter($this->getSubscription());
            $this->logCollection->setOrder('created_at', 'desc');
            $this->logCollection->setPageSize(10);

            // Explicitly hide any records except billing from the frontend.
            $this->logCollection->addFieldToFilter('order_increment_id', ['notnull' => true]);
        }

        return $this->logCollection;
    }
}
