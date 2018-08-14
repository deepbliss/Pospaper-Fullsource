<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

class ModuleEnable
{
    const TIG_POSTNL_MODULE_NAMESPACE = 'TIG_PostNL';

    const AMASTY_STOCKSTATUS_MODULE_NAMESPACE = 'Amasty_Stockstatus';

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    public function __construct(\Magento\Framework\Module\Manager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * @return bool
     */
    public function isPostNlEnable()
    {
        return $this->moduleManager->isEnabled(self::TIG_POSTNL_MODULE_NAMESPACE);
    }

    /**
     * @return bool
     */
    public function isStockStatusEnable()
    {
        return $this->moduleManager->isEnabled(self::AMASTY_STOCKSTATUS_MODULE_NAMESPACE);
    }
}
