<?php
namespace Pos\Custommodule\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;


class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;
    protected $quoteSetupFactory;
    protected $salesSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'show_children_images', [
                'type' => 'int',
                'label' => 'Show Children Images',
                'input' => 'select',
                'required' => false,
                'sort_order' => 160,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]);

            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'show_level_two', [
                'type' => 'int',
                'label' => 'Show Level 2 Sub-Categories',
                'input' => 'select',
                'required' => false,
                'sort_order' => 161,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]);
        }

        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'show_top_selling', [
                'type' => 'int',
                'label' => 'Show Top Selling Block',
                'input' => 'select',
                'required' => false,
                'sort_order' => 162,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]);
        }

        if (version_compare($context->getVersion(), '2.0.3', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'top_selling_skus', [
                'type'     => 'text',
                'label'    => 'Top Selling SKUs',
                'input'    => 'text',
                'sort_order' => 163,
                'is_required' => false,
                'required'  => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
            ]);
        }

        if (version_compare($context->getVersion(), '2.0.4', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'include_top_menu', [
                'type' => 'int',
                'label' => 'Include in Top Menu',
                'input' => 'select',
                'required' => false,
                'sort_order' => 159,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]);
        }

        if (version_compare($context->getVersion(), '2.0.5', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Category::ENTITY, 'short_description', [
                'type' => 'text',
                'label' => 'Short Description',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 1999,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Content',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
            ]);
        }

        if (version_compare($context->getVersion(), '2.0.6', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'short_description',
                'wysiwyg_enabled',
                true
            );
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'short_description',
                'is_html_allowed_on_front',
                true
            );
        }

        if (version_compare($context->getVersion(), '2.0.7', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->updateAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'short_description',
                'is_wysiwyg_enabled',
                true
            );
        }
    }
}