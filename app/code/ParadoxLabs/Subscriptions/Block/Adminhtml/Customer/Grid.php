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

/**
 * Grid grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var  \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusSource;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);

        $this->collectionFactory = $collectionFactory;
        $this->statusSource = $statusSource;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('subscription_grid');
        $this->setDefaultSort('subscription_id');
        $this->setDefaultDir('desc');
        $this->setUseAjax(true);
    }

    /**
     * Apply various selection filters to prepare the sales order grid collection.
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->getReport('subscriptions_listing_data_source')->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'main_table.customer_id',
            (int)$this->getRequest()->getParam('id')
        );

        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'subscription_id',
            [
                'header' => __('ID'),
                'index'  => 'increment_id',
            ]
        );

        $this->addColumn(
            'subscription_description',
            [
                'header' => __('Description'),
                'index'  => 'description',
            ]
        );

        $this->addColumn(
            'subscription_status',
            [
                'header'  => __('Subscription Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => $this->statusSource->getOptionArray(),
            ]
        );

        $this->addColumn(
            'subscription_created_at',
            [
                'header' => __('Purchased'),
                'index'  => 'created_at',
                'type'   => 'datetime',
            ]
        );

        $this->addColumn(
            'subscription_last_run',
            [
                'header' => __('Last Run'),
                'index'  => 'last_run',
                'type'   => 'datetime',
            ]
        );

        $this->addColumn(
            'subscription_next_run',
            [
                'header' => __('Next Run'),
                'index'  => 'next_run',
                'type'   => 'datetime',
            ]
        );

        $this->addColumn(
            'subscription_subtotal',
            [
                'header'   => __('Subtotal'),
                'index'    => 'subtotal',
                'type'     => 'currency',
                'currency' => 'quote_currency_code',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('subscriptions/customer/subscriptionsGrid', ['_current' => true]);
    }

    /**
     * Retrieve the Url for a specified row.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $row
     * @return string
     */
    public function getRowUrl($row)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $row */

        return $this->getUrl('subscriptions/index/view', ['entity_id' => $row->getData('entity_id')]);
    }
}
