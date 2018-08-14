<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Setup;

use Braintree\Exception;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Sales\Setup\SalesSetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Cms\Model\PageFactory;

/**
 * Class Aheadworks\RewardPoints\Setup\InstallData
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * Page factory
     *
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @param QuoteSetupFactory $setupFactory
     * @param SalesSetupFactory $salesSetupFactory
     * @param CategorySetupFactory $categorySetupFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        QuoteSetupFactory $setupFactory,
        SalesSetupFactory $salesSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        PageFactory $pageFactory
    ) {
        $this->quoteSetupFactory = $setupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->pageFactory = $pageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        /** @var SalesSetup $quoteSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        /** @var CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        /**
         * Install eav entity types to the eav/entity_type table
         */
        $attributes = [
            'aw_use_reward_points' => ['type' => Table::TYPE_INTEGER],
            'aw_reward_points_amount' => ['type' => Table::TYPE_DECIMAL],
            'base_aw_reward_points_amount' => ['type' => Table::TYPE_DECIMAL],
            'aw_reward_points' => ['type' => Table::TYPE_INTEGER],
            'aw_reward_points_description' => ['type' => 'varchar'],
        ];

        foreach ($attributes as $attributeCode => $attributeParams) {
            $quoteSetup->addAttribute('quote', $attributeCode, $attributeParams);
            $quoteSetup->addAttribute('quote_address', $attributeCode, $attributeParams);

            $salesSetup->addAttribute('order', $attributeCode, $attributeParams);
            $salesSetup->addAttribute('invoice', $attributeCode, $attributeParams);
            $salesSetup->addAttribute('creditmemo', $attributeCode, $attributeParams);
        }

        $salesSetup->addAttribute('order', 'base_aw_reward_points_invoiced', ['type' => Table::TYPE_DECIMAL]);
        $salesSetup->addAttribute('order', 'aw_reward_points_invoiced', ['type' => Table::TYPE_DECIMAL]);

        $salesSetup->addAttribute('order', 'base_aw_reward_points_refunded', ['type' => Table::TYPE_DECIMAL]);
        $salesSetup->addAttribute('order', 'aw_reward_points_refunded', ['type' => Table::TYPE_DECIMAL]);

        $categorySetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'aw_rp_allow_spending_points',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Allow spending points for products in category',
                'input' => '',
                'class' => '',
                'source' => '',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'group' => 'General',
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => ''
            ]
        );

        $cmsPage = [
            'title' => 'Reward Points',
            'page_layout' => '1column',
            'identifier' => 'aw-reward-points',
            'content_heading' => 'Reward Points',
            'is_active' => 1,
            'stores' => [0],
            'content' => '<p>The Reward Points Program allows you to earn points for certain actions you take '
                . 'on the site. Points are awarded based on making purchases and customer actions such as submitting '
                . 'reviews.</p>',
        ];

        try {
            $this->pageFactory->create()->setData($cmsPage)->save();
        } catch (\Exception $e) {
        }
    }
}
