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

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Uninstall Class
 */
class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
    /**
     * @var \Magento\Catalog\Setup\CategorySetupFactory
     */
    protected $categorySetupFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Magento\SalesSequence\Model\MetaFactory
     */
    protected $sequenceMetaFactory;

    /**
     * Init
     *
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\SalesSequence\Model\MetaFactory $sequenceMetaFactory
     */
    public function __construct(
        \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\SalesSequence\Model\MetaFactory $sequenceMetaFactory
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->helper = $helper;
        $this->storeRepository = $storeRepository;
        $this->sequenceMetaFactory = $sequenceMetaFactory;
    }

    /**
     * Invoked when remove-data flag is set during module uninstall.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->removeProductAttributes($setup);

        $this->removeTables($setup);

        $this->removeSalesSequence($setup);
    }

    /**
     * Remove subscription product attributes and group.
     *
     * @param SchemaSetupInterface $setup
     * @return void
     */
    protected function removeProductAttributes(SchemaSetupInterface $setup)
    {
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        /**
         * Remove product attributes
         */
        $attributes = [
            'subscription_active',
            'subscription_allow_onetime',
            'subscription_intervals',
            'subscription_unit',
            'subscription_length',
            'subscription_unit',
            'subscription_init_adjustment',
            'subscription_price',
        ];

        foreach ($attributes as $attribute) {
            try {
                $categorySetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $attribute);
            } catch (\Exception $e) {
                $this->helper->log('subscriptions', (string)$e);
            }
        }

        /**
         * Remove product attribute group
         */
        try {
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $categorySetup->removeAttributeGroup(
                $entityTypeId,
                $attributeSetId,
                'Subscription'
            );
        } catch (\Exception $e) {
            $this->helper->log('subscriptions', (string)$e);
        }
    }

    /**
     * Remove database tables.
     *
     * @param SchemaSetupInterface $setup
     * @return void
     */
    protected function removeTables(SchemaSetupInterface $setup)
    {
        /**
         * Remove tables
         */
        try {
            $setup->getConnection('checkout')->dropTable(
                $setup->getTable('paradoxlabs_subscription')
            );

            $setup->getConnection('checkout')->dropTable(
                $setup->getTable('paradoxlabs_subscription_log')
            );

            $setup->getConnection()->dropTable(
                $setup->getTable('paradoxlabs_subscription_product_interval')
            );
        } catch (\Exception $e) {
            $this->helper->log('subscriptions', (string)$e);
        }
    }

    /**
     * Remove sales sequence tables and meta.
     *
     * @param SchemaSetupInterface $setup
     * @return void
     */
    protected function removeSalesSequence(SchemaSetupInterface $setup)
    {
        /** @var \Magento\SalesSequence\Model\ResourceModel\Meta $sequenceMetaResource */
        $sequenceMeta = $this->sequenceMetaFactory->create();
        $sequenceMetaResource = $sequenceMeta->getResource();

        $stores = $this->storeRepository->getList();
        foreach ($stores as $store) {
            $sequenceMeta = $sequenceMetaResource->loadByEntityTypeAndStore(
                'subscription',
                $store->getId()
            );

            if ($sequenceMeta instanceof \Magento\SalesSequence\Model\Meta && !empty($sequenceMeta->getId())) {
                // Remove sequence table
                $setup->getConnection()->dropTable(
                    $setup->getTable($sequenceMeta->getData('sequence_table'))
                );

                // Remove sequence meta
                $sequenceMetaResource->delete($sequenceMeta);
            }
        }
    }
}
