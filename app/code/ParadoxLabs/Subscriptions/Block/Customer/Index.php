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
 * Index block: List subscriptions
 *
 * Borrows toolbar from \Magento\Catalog\Block\Product\ListProduct
 */
class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $defaultToolbarBlock = \Magento\Catalog\Block\Product\ProductList\Toolbar::class;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var \Magento\Directory\Model\Currency[]
     */
    protected $currencies = [];

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    protected $statusSource;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Source\Period
     */
    protected $periodSource;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer *Proxy
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource
     * @param \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \ParadoxLabs\Subscriptions\Model\Source\Status $statusSource,
        \ParadoxLabs\Subscriptions\Model\Source\Period $periodSource,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->collectionFactory = $collectionFactory;
        $this->currentCustomer = $currentCustomer;
        $this->currencyFactory = $currencyFactory;
        $this->statusSource = $statusSource;
        $this->periodSource = $periodSource;
    }

    /**
     * Get subscription view URL.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return string
     */
    public function getViewUrl(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        return $this->_urlBuilder->getUrl('*/*/view', ['id' => $subscription->getId()]);
    }

    /**
     * Get subscription edit URL.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return string
     */
    public function getEditUrl(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        return $this->_urlBuilder->getUrl('*/*/edit', ['id' => $subscription->getId()]);
    }

    /**
     * Get the formatted subscription subtotal.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return string
     */
    public function getSubtotal(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
        $currency = $subscription->getData('quote_currency_code');

        if (!isset($this->currencies[$currency])) {
            $this->currencies[$currency] = $this->currencyFactory->create();
            $this->currencies[$currency]->load($currency);
        }

        return $this->currencies[$currency]->formatTxt($subscription->getSubtotal());
    }

    /**
     * Get the subscription status text.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return string
     */
    public function getStatus(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        return $this->statusSource->getOptionText($subscription->getStatus());
    }

    /**
     * Get frequency label (Every ___) for grid.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return \Magento\Framework\Phrase
     */
    public function getFrequencyLabel(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        if ($subscription->getFrequencyCount() === 1) {
            $unitLabel = $this->periodSource->getOptionText($subscription->getFrequencyUnit());

            return __('Every %1', $unitLabel);
        } else {
            $unitLabel = $this->periodSource->getOptionTextPlural($subscription->getFrequencyUnit());

            return __('Every %1 %2', $subscription->getFrequencyCount(), $unitLabel);
        }
    }

    /**
     * Get status source model.
     *
     * @return \ParadoxLabs\Subscriptions\Model\Source\Status
     */
    public function getStatusSource()
    {
        return $this->statusSource;
    }

    /**
     * Get the subscription collection
     *
     * @return \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection
     */
    public function getCollection()
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
            $this->collection->addFieldToFilter('main_table.customer_id', $this->currentCustomer->getCustomerId());
            $this->collection->joinQuoteCurrency();
        }

        return $this->collection;
    }

    /**
     * Initialize the toolbar.
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getCollection();

        // use sortable parameters
        $toolbar->setAvailableOrders([
            'entity_id' => 'Ref #',
            'description' => 'Description',
            'created_at' => 'Purchased',
            'last_run' => 'Last Run',
            'next_run' => 'Next Run',
        ]);
        $toolbar->setDefaultOrder('entity_id');
        $toolbar->setDefaultDirection('desc');
        $toolbar->disableViewSwitcher();
        $toolbar->disableParamsMemorizing();

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);

        $this->getCollection()->load();

        parent::_beforeToHtml();

        return $this;
    }

    /**
     * Retrieve Toolbar block
     *
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getData('toolbar_block_name');
        if ($blockName) {
            /** @var \Magento\Catalog\Block\Product\ProductList\Toolbar $block */
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }

        $block = $this->getLayout()->createBlock($this->defaultToolbarBlock, uniqid(microtime()));

        return $block;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }
}
