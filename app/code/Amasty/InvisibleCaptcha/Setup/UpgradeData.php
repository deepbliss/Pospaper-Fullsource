<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


namespace Amasty\InvisibleCaptcha\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $configWriter;

    /**
     * UpgradeData constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface    $scopeConfig
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    /**
     * Upgrades data for the InvisibleCaptcha module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion() && version_compare($context->getVersion(), '2.0.0', '<')) {
            $captchaUrls = $this->scopeConfig->getValue('aminvisiblecaptcha/general/captchaUrls');
            if ($captchaUrls) {
                $this->configWriter->delete('aminvisiblecaptcha/general/captchaUrls');
                $this->configWriter->save('aminvisiblecaptcha/advanced/captchaUrls', $captchaUrls);
            }

            $captchaSelectors = $this->scopeConfig->getValue('aminvisiblecaptcha/general/captchaSelectors');
            if ($captchaSelectors) {
                $this->configWriter->delete('aminvisiblecaptcha/general/captchaSelectors');
                $this->configWriter->save('aminvisiblecaptcha/advanced/captchaSelectors', $captchaSelectors);
            }
        }
        $setup->endSetup();
    }
}
