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

namespace ParadoxLabs\Subscriptions\Block\Adminhtml\Subscription\View\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * History grid
 */
class History extends \Magento\Backend\Block\Widget\Grid\Extended implements TabInterface
{
    /**
     * @var  \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Agent
     */
    protected $agentSource;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
     * @param \ParadoxLabs\Subscriptions\Model\Source\Agent $agentSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $registry,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource,
        \ParadoxLabs\Subscriptions\Model\Source\Agent $agentSource,
        array $data = []
    ) {
        parent::__construct($context, $backendHelper, $data);

        $this->collectionFactory = $collectionFactory;
        $this->registry = $registry;
        $this->statusSource = $statusSource;
        $this->agentSource = $agentSource;
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('History');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('History');
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('subscription_history_grid');
        $this->setDefaultSort('history_created_at');
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
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $subscription = $this->registry->registry('current_subscription');

        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\UiCollection $collection */
        $collection = $this->collectionFactory->getReport('subscriptions_log_data_source')->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'main_table.subscription_id',
            $subscription->getId()
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
            'history_created_at',
            [
                'header' => __('Date'),
                'index'  => 'created_at',
                'filter_index' => 'main_table.created_at',
                'type'   => 'datetime',
            ]
        );

        $this->addColumn(
            'history_status',
            [
                'header'  => __('Subscription Status'),
                'index'   => 'status',
                'filter_index' => 'main_table.status',
                'type'    => 'options',
                'options' => $this->statusSource->getOptionArray(),
            ]
        );

        $this->addColumn(
            'history_order_increment_id',
            [
                'header' => __('Order #'),
                'index'  => 'order_increment_id',
            ]
        );

        $this->addColumn(
            'history_agent_id',
            [
                'header'  => __('Agent'),
                'index'   => 'agent_id',
                'type'    => 'options',
                'options' => $this->agentSource->getOptionArray(),
            ]
        );

        $this->addColumn(
            'history_description',
            [
                'header' => __('Description'),
                'index'  => 'description',
                'filter_index' => 'main_table.description',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/logGrid', ['_current' => true]);
    }

    /**
     * Retrieve the Url for a specified sales order row.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Log $row
     * @return string
     */
    public function getRowUrl($row)
    {
        if (!empty($row->getData('order_id'))) {
            return $this->getUrl('sales/order/view', ['order_id' => $row->getData('order_id')]);
        }

        return false;
    }
}
