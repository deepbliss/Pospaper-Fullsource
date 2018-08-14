<?php
namespace Webkul\AdvancedLayeredNavigation\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Setup\CategorySetupFactory; 

class InstallData implements InstallDataInterface
{
    /**
    * Category setup factory
    *
    * @var CategorySetupFactory
    */
    private $categorySetupFactory;
    /**
    * Init
    *
    * @param CategorySetupFactory $categorySetupFactory
    */
    public function __construct(CategorySetupFactory $categorySetupFactory)
    {
         $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
         $installer = $setup;
         $installer->startSetup();
         $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
         $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Category::ENTITY);
         $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

         $categorySetup->addAttribute(
         \Magento\Catalog\Model\Category::ENTITY, 'com_model_display', [
            'type' => 'int',
            'label' => 'Compatible Model Display',
            'input' => 'select',
            'required' => false,
            'sort_order' => 100,
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'group' => 'General Information',
            'is_used_in_grid' => true,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true,
         ]
         );
         $installer->endSetup();
    }
}