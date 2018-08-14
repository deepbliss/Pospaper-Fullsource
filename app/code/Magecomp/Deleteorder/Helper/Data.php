<?php
namespace Magecomp\Deleteorder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_configScopeConfigInterface;

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function isEnabled()
	{
		return $this->scopeConfig->getValue('deleteorder/general/enable', ScopeInterface::SCOPE_STORE);
    }
	
	public function getOrderButtonLabel()
	{
		return $this->scopeConfig->getValue('deleteorder/general/btnheading', ScopeInterface::SCOPE_STORE);
    }
}