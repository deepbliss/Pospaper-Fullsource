<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Amasty\Base\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var \Amasty\Base\Helper\Module
     */
    private $moduleHelper;

    public function __construct(
        \Magento\Framework\App\State $appState,
        \Psr\Log\LoggerInterface $logger,
        \Amasty\Base\Helper\Module $moduleHelper
    ) {
        $this->logger = $logger;
        $this->appState = $appState;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     * @return void
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->appState->emulateAreaCode(
            \Magento\Framework\App\Area::AREA_ADMINHTML,
            [$this, 'upgradeCallback'],
            [$setup, $context]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgradeCallback(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.5.0', '<')) {
            try {
                $this->moduleHelper->reload();
            } catch (\Exception $ex) {
                $this->logger->critical($ex);
            }
        }

        $setup->endSetup();
    }
}
