<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Block\Onepage\CustomBlock;

use Magento\Store\Model\ScopeInterface;

class Block extends \Magento\Cms\Block\Block
{
    /**
     * @var \Amasty\Checkout\Model\Config
     */
    private $config;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Amasty\Checkout\Model\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $filterProvider, $storeManager, $blockFactory, $data);
        $this->config = $config;
    }

    /**
     * @return int
     */
    public function getBlockId()
    {
        return $this->config->getCustomBlockIdByPosition($this->getPosition());
    }
}
