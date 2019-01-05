<?php
/**
 * Copyright © 2015 Mp . All rights reserved.
 */
namespace Pos\Custommodule\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $storeManager;

	/**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
	    \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		parent::__construct($context);
		$this->storeManager = $storeManager;
	}

	public function getStore()
    {
        return $this->storeManager->getStore();
	}
}