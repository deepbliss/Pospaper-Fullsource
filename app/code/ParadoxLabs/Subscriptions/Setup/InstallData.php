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
 * Install attributes
 */
class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
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
     * Init
     *
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\SalesSequence\Model\Builder $sequenceBuilder
     * @param \Magento\SalesSequence\Model\Config $sequenceConfig
     */
    public function __construct(
        \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\SalesSequence\Model\Builder $sequenceBuilder,
        \Magento\SalesSequence\Model\Config $sequenceConfig
    ) {
        $this->categorySetupFactory = $categorySetupFactory;
        $this->storeRepository = $storeRepository;
        $this->sequenceBuilder = $sequenceBuilder;
        $this->sequenceConfig = $sequenceConfig;
    }

    /**
     * Installs data for the module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->createProductAttributes($setup);

        $this->createSalesSequence($setup);
    }

    /**
     * Create subscription sales sequences for increment IDs.
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    protected function createSalesSequence(ModuleDataSetupInterface $setup)
    {
        $stores = $this->storeRepository->getList();

        foreach ($stores as $store) {
            $storeId = $store->getId();

            $this->sequenceBuilder->setPrefix($storeId ?: $this->sequenceConfig->get('prefix'))
                ->setSuffix($this->sequenceConfig->get('suffix'))
                ->setStartValue($this->sequenceConfig->get('startValue'))
                ->setStoreId($storeId)
                ->setStep($this->sequenceConfig->get('step'))
                ->setWarningValue($this->sequenceConfig->get('warningValue'))
                ->setMaxValue($this->sequenceConfig->get('maxValue'))
                ->setEntityType('subscription')
                ->create();
        }
    }

    /**
     * Create subscription product attributes.
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    protected function createProductAttributes(ModuleDataSetupInterface $setup)
    {
        /** @var \Magento\Catalog\Setup\CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        /**
         * Attributes:
         * subscription_active
         * subscription_allow_onetime
         * subscription_intervals
         * subscription_unit
         * subscription_length
         * subscription_price
         * subscription_init_adjustment
         */

        // Add new tab
        $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

        $categorySetup->addAttributeGroup($entityTypeId, $attributeSetId, 'Subscription', 65);
        $categorySetup->updateAttributeGroup(
            $entityTypeId,
            $attributeSetId,
            'Subscription',
            'attribute_group_code',
            'subscription'
        );
        $categorySetup->updateAttributeGroup(
            $entityTypeId,
            $attributeSetId,
            'Subscription',
            'tab_group_code',
            'advanced'
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_active',
            [
                'type'                  => 'int',
                'label'                 => 'Enable',
                'input'                 => 'select',
                'source'                => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'sort_order'            => 100,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => null,
                'group'                 => 'Subscription',
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'used_for_promo_rules'  => true,
                'required'              => false,
                'default'               => '0',
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_allow_onetime',
            [
                'type'                  => 'int',
                'label'                 => 'Allow one-time purchase',
                'input'                 => 'select',
                'source'                => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'sort_order'            => 500,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => 'simple,virtual,downloadable,configurable',
                'group'                 => 'Subscription',
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'used_for_promo_rules'  => false,
                'required'              => false,
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_intervals',
            [
                'type'                  => 'text',
                'label'                 => 'Interval(s)',
                'input'                 => 'text',
                'backend'               => \ParadoxLabs\Subscriptions\Model\Attribute\Backend\Intervals::class,
                'sort_order'            => 200,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => null,
                'group'                 => 'Subscription',
                'note'                  => 'Enter the subscription interval(s), in conjunction with unit below. EG.'
                    . ' 1 month, 90 days, etc. To give multiple options, separate the numbers by comma: 30,45,60,90',
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'required'              => false,
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_unit',
            [
                'type'                  => 'varchar',
                'label'                 => 'Unit',
                'input'                 => 'select',
                'source'                => \ParadoxLabs\Subscriptions\Model\Source\Period::class,
                'sort_order'            => 300,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => null,
                'group'                 => 'Subscription',
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'used_for_promo_rules'  => true,
                'required'              => false,
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_length',
            [
                'type'                  => 'varchar',
                'label'                 => 'Length',
                'input'                 => 'text',
                'frontend_class'        => 'validate-number',
                'sort_order'            => 400,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => null,
                'group'                 => 'Subscription',
                'note'                  => 'Number of intervals the subscription should run. 0 for indefinitely.',
                'used_for_promo_rules'  => true,
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'required'              => false,
            ]
        );

        // These price attributes render weird. It's because the core has hardcoded CSS tied to field classes
        // (field-price et al.). ...
        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_price',
            [
                'type'                  => 'decimal',
                'label'                 => 'Installment Price',
                'input'                 => 'text',
                'frontend_class'        => 'validate-number',
                'sort_order'            => 500,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => 'simple,virtual,downloadable,configurable',
                'group'                 => 'Subscription',
                'note'                  => 'Regular price (for subscriptions only). Any lower price (regular, group,'
                    . 'tier, etc.) will override this.',
                'used_for_promo_rules'  => true,
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'required'              => false,
            ]
        );

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'subscription_init_adjustment',
            [
                'type'                  => 'decimal',
                'label'                 => 'Initial Order Adjustment Price',
                'input'                 => 'text',
                'frontend_class'        => 'validate-number',
                'sort_order'            => 600,
                'global'                => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'apply_to'              => 'simple,virtual,downloadable,configurable',
                'group'                 => 'Subscription',
                'note'                  => 'Price to adjust the initial order by (for subscriptions only). '
                    . 'A positive adjustment will make the first billing cost more. '
                    . 'A negative adjustment will make it cost less (down to $0.00).',
                'is_used_in_grid'       => true,
                'is_visible_in_grid'    => false,
                'is_filterable_in_grid' => true,
                'required'              => false,
            ]
        );
    }
}
