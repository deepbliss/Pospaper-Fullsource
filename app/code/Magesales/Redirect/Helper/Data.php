<?php
namespace Magesales\Redirect\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_objectManager;
	protected $_scopeConfig;
	const XML_PATH_AUTOREPLY_ENABLED = 'redirect/general/enable';
	
	public function __construct(\Magento\Framework\App\Helper\Context $context,\Magento\Framework\ObjectManagerInterface $objectManager)
	{
		$this->_objectManager = $objectManager;
		parent::__construct($context);
	}
	
	public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_AUTOREPLY_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}

