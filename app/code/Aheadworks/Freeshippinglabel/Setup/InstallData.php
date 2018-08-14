<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Setup;

use Aheadworks\Freeshippinglabel\Model\Source\FontWeight;
use Aheadworks\Freeshippinglabel\Model\Source\PageType;
use Aheadworks\Freeshippinglabel\Model\Source\Position;
use Aheadworks\Freeshippinglabel\Model\Source\TextAlign;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterface;
use Aheadworks\Freeshippinglabel\Api\LabelRepositoryInterface;
use Aheadworks\Freeshippinglabel\Api\Data\LabelInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class InstallData
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @package Aheadworks\Freeshippinglabel\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var LabelRepositoryInterface
     */
    private $labelRepository;

    /**
     * @var LabelInterfaceFactory
     */
    private $labelDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param \Magento\Framework\App\State $state
     * @param LabelRepositoryInterface $labelRepository
     * @param LabelInterfaceFactory $labelDataFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        LabelRepositoryInterface $labelRepository,
        LabelInterfaceFactory $labelDataFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->state = $state;
        $this->labelRepository = $labelRepository;
        $this->labelDataFactory = $labelDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // Emulate area for label setup
        $this->state->emulateAreaCode(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
            [$this, 'process'],
            []
        );
    }

    /**
     * Setup sample data
     *
     * @throws \Exception
     * @return void
     */
    public function process()
    {
        $data = [
            'id' => 1,
            'is_enabled' => 0,
            'customer_group_ids' => [],
            'goal' => 100,
            'page_type' => PageType::ALL_PAGES,
            'position' => Position::PAGE_TOP_FIXED,
            'delay' => 3,
            'content' => [
                [
                    'store_id' => 0,
                    'content_type' => 'empty_cart',
                    'message' => 'Free shipping on orders over {{ruleGoal}}'
                ],
                [
                    'store_id' => 0,
                    'content_type' => 'not_empty_cart',
                    'message' => '{{ruleGoalLeft}} left for free shipping'
                ],
                [
                    'store_id' => 0,
                    'content_type' => 'goal_reached',
                    'message' => 'Great! your order will be delivered for free!'
                ]
            ],
            'font_name' => 'Open Sans',
            'font_size' => 16,
            'font_weight' => FontWeight::MEDIUM,
            'font_color' => '#222222',
            'goal_font_color' => '#ee6655',
            'background_color' => '#dddddd',
            'text_align' => TextAlign::CENTER,
            'custom_css' => ''
        ];
        try {
            $labelDataObject = $this->labelDataFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $labelDataObject,
                $data,
                LabelInterface::class
            );

            $this->labelRepository->save($labelDataObject);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
