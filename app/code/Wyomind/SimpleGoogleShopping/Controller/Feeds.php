<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Controller;

/**
 * Simple google shopping frontend controller
 */
abstract class Feeds extends \Magento\Framework\App\Action\Action
{

    protected $_coreHelper = null;
    protected $_resultRawFactory = null;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Wyomind\Core\Helper\Data $coreHelper
    )
    {
        $this->_coreHelper = $coreHelper;
        $this->_resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * execute action
     */
    abstract public function execute();
}
