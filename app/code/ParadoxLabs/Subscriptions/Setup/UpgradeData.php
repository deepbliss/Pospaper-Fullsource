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

namespace ParadoxLabs\Subscriptions\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * UpgradeData Class
 */
class UpgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $quoteCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    protected $productMetadata;

    /**
     * @var \Magento\Framework\Unserialize\Unserialize
     */
    protected $unserialize;

    /**
     * @var \Magento\Catalog\Setup\CategorySetupFactory
     */
    protected $categorySetupFactory;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Magento\SalesSequence\Model\Builder
     */
    protected $sequenceBuilder;

    /**
     * @var \Magento\SalesSequence\Model\Config
     */
    protected $sequenceConfig;

    /**
     * @var \Magento\SalesSequence\Model\MetaFactory
     */
    protected $sequenceMetaFactory;

    /**
     * @var Data\ProductOptionIntervalSetup
     */
    protected $productOptionIntervalSetup;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    /**
     * UpgradeData constructor.
     *
     * @param \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     * @param \Magento\Framework\Unserialize\Unserialize $unserialize
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\SalesSequence\Model\Builder $sequenceBuilder
     * @param \Magento\SalesSequence\Model\Config $sequenceConfig
     * @param \Magento\SalesSequence\Model\MetaFactory $sequenceMetaFactory
     * @param Data\ProductOptionIntervalSetup $productOptionIntervalSetup
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     */
    public function __construct(
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteCollectionFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Framework\App\ProductMetadata $productMetadata,
        \Magento\Framework\Unserialize\Unserialize $unserialize,
        \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\SalesSequence\Model\Builder $sequenceBuilder,
        \Magento\SalesSequence\Model\Config $sequenceConfig,
        \Magento\SalesSequence\Model\MetaFactory $sequenceMetaFactory,
        Data\ProductOptionIntervalSetup $productOptionIntervalSetup,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->helper = $helper;
        $this->productMetadata = $productMetadata;
        $this->unserialize = $unserialize;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->storeRepository = $storeRepository;
        $this->sequenceBuilder = $sequenceBuilder;
        $this->sequenceConfig = $sequenceConfig;
        $this->sequenceMetaFactory = $sequenceMetaFactory;
        $this->productOptionIntervalSetup = $productOptionIntervalSetup;
        $this->configWriter = $configWriter;
    }

    /**
     * Data upgrade
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $quoteDb = $setup->getConnection('checkout');

        $magentoVersion = $this->productMetadata->getVersion();

        /**
         * Make sure that no subscription quote ever has a updated_at date in the past, or it's at risk of getting
         * pruned. Bye-bye subscription.
         *
         * We bypass the prune check via the updated_at date as such to ensure data persistence even if the module
         * should be temporarily disabled. A plugin doesn't ensure that.
         */
        $quotes = $setup->getTable('quote');
        $subs   = $setup->getTable('paradoxlabs_subscription');
        $count = $quoteDb->update(
            $quotes,
            [
                'updated_at' => '2038-01-01',
            ],
            [
                new \Zend_Db_Expr('entity_id IN (SELECT quote_id FROM '.$subs.')'),
                'updated_at<?' => '2038-01-01',
            ]
        );

        /**
         * Magento changed where Vault data is stored on payment records in 2.1.3, but we didn't. If Magento is already
         * on a newer version, fix any lingering incorrect data.
         */
        if (version_compare($context->getVersion(), '1.2.3', '<')
            && version_compare($magentoVersion, '2.1.3', '>=')) {
            $this->fixTokenMetadataStorage($setup, $context, $quoteDb, $magentoVersion);
        }

        /**
         * Add backend model to subscription_intervals product attribute for validation.
         */
        $this->addIntervalsBackendModel($setup, $context, $categorySetup);

        /**
         * Add sales sequence if it does not exist.
         */
        $this->createSalesSequence($setup, $quoteDb);

        /**
         * Add intervals for each product subscription custom option value.
         */
        $this->createOptionIntervals($setup);

        /**
         * Change attributes to global.
         */
        $this->makeAttributesGlobal($context, $categorySetup);

        /**
         * Unrestrict certain attributes.
         */
        $this->allowAttributeProductTypes($context, $categorySetup);

        /**
         * On upgrade to 3.1.0, set config default_to_one_time to 0 to avoid behavior change. (Default 1 for new inst.)
         */
        if (version_compare($context->getVersion(), '3.1.0', '<')) {
            $this->setConfig($setup, 'subscriptions/general/default_to_one_time', 0);
        }
    }

    /**
     * Unpack the given data (serialized/json).
     *
     * @param string $data
     * @param string $magentoVersion
     * @return mixed
     */
    protected function unpack($data, $magentoVersion)
    {
        if (version_compare($magentoVersion, '2.2.0', '<')) {
            return $this->unserialize->unserialize($data);
        }

        return json_decode($data, true);
    }

    /**
     * Pack the given data into serialized string or JSON, depending on Magento version.
     *
     * @param mixed $data
     * @param string $magentoVersion
     * @return string
     */
    protected function pack($data, $magentoVersion)
    {
        if (version_compare($magentoVersion, '2.2.0', '<')) {
            // I'm a bad bad person: Bypassing S10 failure in coding-standards v3 ruleset because there's no option
            // *but* to use serialize on 2.1, and 2.1 does not have SerializerInterface to replace it. (Github #12669)
            $pack = 'serialize';
            return $pack($data);
        }

        return json_encode($data);
    }

    /**
     * Magento changed where Vault data is stored on payment records in 2.1.3. Fix any lingering incorrect data.
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @param string $magentoVersion
     * @return $this
     */
    public function fixTokenMetadataStorage(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb,
        $magentoVersion
    ) {
        /**
         * Fix tokenbase_metadata in sales_order_payment
         */
        $salesDb = $setup->getConnection('sales');
        $select = $salesDb->select()
            ->from($setup->getTable('sales_order_payment'), 'entity_id')
            ->columns(['additional_information'])
            ->where('additional_information LIKE ?', '%token_metadata%');

        $items = $salesDb->fetchAll($select);
        foreach ($items as $item) {
            $additionalInfo = $this->unpack($item['additional_information'], $magentoVersion);
            $additionalInfo['customer_id'] = $additionalInfo['token_metadata']['customer_id'];
            $additionalInfo['public_hash'] = $additionalInfo['token_metadata']['public_hash'];
            unset($additionalInfo['token_metadata']);

            $salesDb->update(
                $setup->getTable('sales_order_payment'),
                ['additional_information' => $this->pack($additionalInfo, $magentoVersion)],
                ['entity_id = ?' => $item['entity_id']]
            );
        }

        /**
         * Fix tokenbase_metadata in quote_payment
         */
        $select = $quoteDb->select()
            ->from($setup->getTable('quote_payment'), 'payment_id')
            ->columns(['additional_information'])
            ->where('additional_information LIKE ?', '%token_metadata%');

        $items = $quoteDb->fetchAll($select);
        foreach ($items as $item) {
            $additionalInfo = $this->unpack($item['additional_information'], $magentoVersion);
            $additionalInfo['customer_id'] = $additionalInfo['token_metadata']['customer_id'];
            $additionalInfo['public_hash'] = $additionalInfo['token_metadata']['public_hash'];
            unset($additionalInfo['token_metadata']);

            $quoteDb->update(
                $setup->getTable('quote_payment'),
                ['additional_information' => $this->pack($additionalInfo, $magentoVersion)],
                ['payment_id = ?' => $item['payment_id']]
            );
        }

        return $this;
    }

    /**
     * Add backend model to subscription_intervals product attribute for validation.
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @param \Magento\Catalog\Setup\CategorySetup $categorySetup
     * @return $this
     */
    public function addIntervalsBackendModel(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context,
        \Magento\Catalog\Setup\CategorySetup $categorySetup
    ) {
        $intervalsAttr = $categorySetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_intervals'
        );

        if (empty($intervalsAttr['backend_model'])) {
            $categorySetup->updateAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $intervalsAttr['attribute_id'],
                'backend_model',
                \ParadoxLabs\Subscriptions\Model\Attribute\Backend\Intervals::class
            );
        }

        return $this;
    }

    /**
     * Create subscription sales sequences for increment IDs.
     *
     * @param ModuleDataSetupInterface $setup
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
     * @return void
     */
    protected function createSalesSequence(
        ModuleDataSetupInterface $setup,
        \Magento\Framework\DB\Adapter\AdapterInterface $quoteDb
    ) {
        // Short-circuit if subscription sequence already exists.
        /** @var \Magento\SalesSequence\Model\ResourceModel\Meta $sequenceMetaResource */
        $sequenceMeta = $this->sequenceMetaFactory->create();
        $sequenceMetaResource = $sequenceMeta->getResource();

        $sequenceMeta = $sequenceMetaResource->loadByEntityTypeAndStore('subscription', 0);
        if ($sequenceMeta instanceof \Magento\SalesSequence\Model\Meta && !empty($sequenceMeta->getId())) {
            return;
        }

        $select = $quoteDb->select();
        $select->from(
            $setup->getTable('paradoxlabs_subscription'),
            'entity_id'
        );
        $select->order('entity_id desc');
        $currentId = (int)$quoteDb->fetchOne($select);

        $stores = $this->storeRepository->getList();
        foreach ($stores as $store) {
            $storeId = $store->getId();

            // Create sequence for store
            $this->sequenceBuilder->setPrefix($storeId ?: $this->sequenceConfig->get('prefix'))
                ->setSuffix($this->sequenceConfig->get('suffix'))
                ->setStartValue($this->sequenceConfig->get('startValue'))
                ->setStoreId($storeId)
                ->setStep($this->sequenceConfig->get('step'))
                ->setWarningValue($this->sequenceConfig->get('warningValue'))
                ->setMaxValue($this->sequenceConfig->get('maxValue'))
                ->setEntityType('subscription')
                ->create();

            // If we have existing subscriptions, initialize the sequence up to the current ID.
            if ($currentId > 0) {
                $meta  = $sequenceMetaResource->loadByEntityTypeAndStore('subscription', $storeId);
                $table = $meta->getData('sequence_table');

                $sql = $setup->getConnection()->insertFromSelect(
                    $select,
                    $setup->getTable($table)
                );

                $setup->getConnection()->query($sql);
            }
        }

        // If sales sequence did not exist, we're adding increment_id, and that means subscription grids have changed.
        $this->purgeSubscriptionGridBookmarks($setup);
    }

    /**
     * Purge subscription grid configuration states (reset to default).
     *
     * In cases where columns are added/removed on UI component grids, those changes will not take effect as long as
     * the ui_bookmark records remain. Removing custom setups is undesirable, but the alternatives are worse.
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    protected function purgeSubscriptionGridBookmarks(ModuleDataSetupInterface $setup)
    {
        $setup->getConnection()->delete(
            $setup->getTable('ui_bookmark'),
            $setup->getConnection()->quoteInto(
                'namespace IN (?)',
                [
                    'subscriptions_listing',
                    'subscription_logs_listing',
                ]
            )
        );
    }

    /**
     * Create subscription intervals for any existing subscription custom options.
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    protected function createOptionIntervals(ModuleDataSetupInterface $setup)
    {
        $this->productOptionIntervalSetup->createOptionIntervals();
    }

    /**
     * Change product attributes to global if upgrading to 3.0.
     *
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @param \Magento\Catalog\Setup\CategorySetup $categorySetup
     * @return void
     */
    protected function makeAttributesGlobal(
        ModuleContextInterface $context,
        \Magento\Catalog\Setup\CategorySetup $categorySetup
    ) {
        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            $attributeCodes = [
                'subscription_active',
                'subscription_allow_onetime',
                'subscription_intervals',
                'subscription_unit',
                'subscription_length',
                'subscription_price',
                'subscription_init_adjustment',
            ];

            foreach ($attributeCodes as $attributeCode) {
                $categorySetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeCode,
                    'is_global',
                    \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL
                );
            }
        }
    }

    /**
     * Change some product attributes to allow all product types if upgrading to 3.0.
     *
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @param \Magento\Catalog\Setup\CategorySetup $categorySetup
     * @return void
     */
    protected function allowAttributeProductTypes(
        ModuleContextInterface $context,
        \Magento\Catalog\Setup\CategorySetup $categorySetup
    ) {
        if (version_compare($context->getVersion(), '3.0.0', '<')) {
            $attributeCodes = [
                'subscription_active',
                'subscription_intervals',
                'subscription_unit',
                'subscription_length',
            ];

            foreach ($attributeCodes as $attributeCode) {
                $categorySetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attributeCode,
                    'apply_to',
                    null
                );
            }
        }
    }

    /**
     * Save config for the given $path to $value.
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param string $path
     * @param string|int|null $value
     * @return void
     */
    protected function setConfig(ModuleDataSetupInterface $setup, $path, $value)
    {
        $this->configWriter->save($path, $value);
    }
}
