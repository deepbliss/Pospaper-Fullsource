<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


namespace Amasty\InvisibleCaptcha\Model\Config\Source;

class Extension implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var string
     */
    private $extension = '';

    /**
     * Extension constructor.
     *
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param string                            $moduleName
     */
    public function __construct(
        \Magento\Framework\Module\Manager $moduleManager,
        $moduleName = ''
    ) {
        $this->moduleManager = $moduleManager;
        $this->extension = $moduleName;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->moduleManager->isEnabled($this->extension)) {
            return [['value' => 1, 'label' => __('Yes')], ['value' => 0, 'label' => __('No')]];
        }
        return [['value' => -1, 'label' => __('Not Installed')]];
    }
}
