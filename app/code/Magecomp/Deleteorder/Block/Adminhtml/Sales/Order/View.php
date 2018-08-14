<?php 
namespace Magecomp\Deleteorder\Block\Adminhtml\Sales\Order;

use Magecomp\Deleteorder\Helper\Data as HelperData;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
use Magento\Sales\Helper\Reorder;
use Magento\Sales\Model\Config;

class View extends OrderView 
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    public function  __construct(Context $context, 
        Registry $registry, 
        Config $salesConfig, 
        Reorder $reorderHelper, 
        HelperData $helperData, 
        array $data = []) 
	{
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);
    }
	
	protected function _construct()
    {
		
		
		parent::_construct();
		if (!$this->_helperData->isEnabled()) return;
		
		$message = __('Do you want to delete this order?');
		$this->addButton(
			'delete_btn',
			[
				'label' => __($this->_helperData->getOrderButtonLabel()),
				'class' => 'go',
				'onclick'   => 'deleteConfirm(\''.$message.'\', \'' . $this->getDeleteOrderUrl() . '\')'
			]
		);
	}
	
    public function getDeleteOrderUrl()
	{
        return $this->getUrl('deleteorder/deleteorder/deleteorder',['_current'=>true]);
    }	
}

?>