<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Controller\Index;

/**
 * Action Index
 */
class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * Execute action
     */
    protected $_megamenuFactory;
    /**
     * Action constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magestore\Megamenu\Model\MegamenuFactory $megamenuFactory
    ) {
        $this->_megamenuFactory = $megamenuFactory;
        parent::__construct($context);
    }
    /**
     * Execute action
     */
    public function execute()
    {
        $items = $this->_megamenuFactory->create()->getCollection();
        $this->_typeListInterface->cleanType('block_html');
        $this->_typeListInterface->cleanType('full_page');
        foreach($items as $item) {
            $item->saveItem();
        }
        $this->_typeListInterface->cleanType('block_html');
        $this->_typeListInterface->cleanType('full_page');
        echo 'done!';
    }
}
