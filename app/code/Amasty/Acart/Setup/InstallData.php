<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    public function __construct(
        \Magento\Framework\App\State $appState
    ) {
        $this->appState = $appState;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup->createMigrationSetup();
        $setup->startSetup();

        $this->appState->emulateAreaCode('frontend', [$this, 'createEmailTemplate']);
        $installer->doUpdateClassAliases();

        $setup->endSetup();
    }

    /**
     * @return void
     */
    public function createEmailTemplate()
    {
        $templateCode = 'amasty_acart_template';

        $template = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magento\Email\Model\Template');

        $template->setForcedArea($templateCode);

        $template->loadDefault($templateCode);

        $template->setData('orig_template_code', $templateCode);

        $template->setData('template_variables', \Zend_Json::encode($template->getVariablesOptionArray(true)));

        $template->setData('template_code', 'Amasty: Abandoned Cart Reminder');

        $template->setTemplateType(\Magento\Email\Model\Template::TYPE_HTML);

        $template->setId(null);

        $template->save();
    }
}
