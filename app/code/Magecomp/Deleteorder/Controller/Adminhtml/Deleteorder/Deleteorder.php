<?php

namespace Magecomp\Deleteorder\Controller\Adminhtml\Deleteorder;

use Magecomp\Deleteorder\Model\OrderFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;

class Deleteorder extends AbstractDeleteorder
{
    /**
     * @var OrderFactory
     */
    protected $_modelOrderFactory;

    public function __construct(Context $context, 
        OrderFactory $modelOrderFactory)
    {
        $this->_modelOrderFactory = $modelOrderFactory;
        parent::__construct($context);
    }

	public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
		if (empty($orderId)) 
		{
            $this->messageManager->addError(__('There is no order to process'));
            $this->_redirect('sales/order/index');
            return;
        }
		
		try 
		{
           if($this->_modelOrderFactory->create()->deleteOrder([$orderId]));
            	$this->messageManager->addSuccess(__('Order successfully deleted'));
        } 
		catch (\Exception $e) 
		{
			$this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('sales/order/index');
		return;
    }
}
